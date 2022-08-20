<?php

namespace App\Models;

use App\Jobs\increaseViewCount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use mysql_xdevapi\Exception;

/**
 * @property int id
 * @property int user_id
 * @property string path
 * @property int view_cnt
 * @property mixed created_at
 * @property mixed updated_at
 */
class Photo extends Model
{
    public static function createFromFile($file, $user)
    {
        $photo = new Photo();
        DB::transaction(function () use ($photo, $file, $user) {
            $photo->setAttribute('user_id', $user->id)->save();
            $path = $user->id . '/' . $photo->created_at->format('Y-M-d/H');
            $name = $photo->id . '-' . hash('sha3-256', $file) . '.jpg';
            if (!Storage::putFileAs($path, $file, $name))
                throw new Exception();
            $photo->setAttribute('path', $path . '/' . $name)->save();
        });
        return is_null($photo->path) ? false : $photo;
    }

    public static function gray($image)
    {
        $width = imagesx($image);
        $height = imagesy($image);
        for ($x = 0; $x < $width; $x++)
            for ($y = 0; $y < $height; $y++)
                imagesetpixel($image, $x, $y, self::monoChannel(imagecolorat($image, $x, $y)));
        return $image;
    }

    private static function monoChannel($color): int
    {
        $b = $color & 0xFF;
        $g = ($color >> 8) & 0xFF;
        $r = ($color >> 16) & 0xFF;
        return 0x010101 * (int)(($r + $g + $b) / 3);
    }

    public static function blur($image, $size)
    {
        $width = imagesx($image);
        $height = imagesy($image);
        for ($x = 0; $x < $width; $x += $size)
            for ($y = 0; $y < $height; $y += $size) {
                $RectWidth = min($size, $width - $x);
                $RectHeight = min($size, $height - $y);
                $color = self::mixer(imagecrop($image, ['x' => $x, 'y' => $y, 'width' => $RectWidth, 'height' => $RectHeight]));
                imagefilledrectangle($image, $x, $y, $x + $RectWidth, $y + $RectHeight, $color);
//                for ($Rx = 0; $Rx < $RectWidth; $Rx++)
//                    for ($Ry = 0; $Ry < $RectHeight; $Ry++)
//                        imagesetpixel($image, $x + $Rx, $y + $Ry, $color);
            }
        return $image;
    }

    private static function mixer($image): int
    {
        $sx = imagesx($image);
        $sy = imagesy($image);
        $sum_r = 0;
        $sum_g = 0;
        $sum_b = 0;
        $cnt = 0;
        for ($i = 1; $i < $sx; $i++)
            for ($j = 1; $j < $sy; $j++) {
                $color = imagecolorat($image, $i, $j);
                $sum_r += $color & 0xFF0000;
                $sum_g += $color & 0x00FF00;
                $sum_b += $color & 0x0000FF;
                $cnt++;
            }
        $sum_r = ($sum_r / $cnt) & 0xFF0000;
        $sum_g = ($sum_g / $cnt) & 0x00FF00;
        $sum_b = ($sum_b / $cnt) & 0x0000FF;
        return $sum_r + $sum_g + $sum_b;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getImage()
    {
        increaseViewCount::dispatch($this->id);
        return imagecreatefromstring(Storage::get($this->path));
    }

    public function delete(): ?bool
    {
        Storage::delete($this->path);
        return parent::delete();
    }
}
