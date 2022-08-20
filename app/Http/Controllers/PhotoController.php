<?php /** @noinspection PhpUnusedParameterInspection */

namespace App\Http\Controllers;

use App\Http\Requests\PhotoRequest;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class PhotoController extends Controller
{
    public function index()
    {
        return view('Photo.index', ['data' => Auth::user()->photos]);
    }

    public function create()
    {
        return view('Photo.create');
    }

    public function store(Request $request)
    {
        $request->validate(['photo' => 'mimetypes:image/jpeg']);
        $photo = Photo::createFromFile($request->file('photo'), Auth::user());
        if ($photo) return redirect('/photos/' . $photo->id . '/edit');
        else return redirect()->back()->with('status', 'Failed');
    }

    public function show(Photo $photo, PhotoRequest $request)
    {
        $file = $photo->getImage();
        switch ($request->get('action')) {
            case 'gray':
                $result = Photo::gray($file);
                break;
            case 'blur':
                $result = Photo::blur($file, $request->get('size', 100));
                break;
            default:
                $result = $file;
        }
        header('Content-Type: image/jpeg');
        imagejpeg($result);
    }

    public function edit(Photo $photo, PhotoRequest $request)
    {
        return view('Photo.edit', ['id' => $photo->id]);
    }

    public function destroy(Photo $photo, PhotoRequest $request)
    {
        $photo->delete();
        return redirect('/photos');
    }
}
