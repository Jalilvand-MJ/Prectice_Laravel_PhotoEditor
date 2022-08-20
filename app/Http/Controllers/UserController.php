<?php

namespace App\Http\Controllers;

use App\Jobs\sendSMS;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function phoneEnter()
    {
        if (Auth::check()) return redirect('/photos');
        return view('User.phone');
    }

    public function codeSend(Request $request)
    {
        $request->validate(['phone' => 'required|regex:/(09)[0-9]{9}/']);
        $phone = $request->get('phone');
        if (is_null($phone)) return redirect('/login');
        Session::put('phone', $phone);
        sendSMS::dispatch($phone, 'SMS_PATTERN_OTP', self::randomCode($phone));
        return Redirect('/verify');
    }

    private function randomCode($phone): int
    {
        try {
            $Code = random_int(0, 99999);
        } catch (Exception $e) {
            $Code = rand(0, 99999);
        }
        Cache::put($phone, $Code, now()->addMinutes(3));
        return $Code;
    }

    public function codeEnter()
    {
        if (!Session::exists('phone')) return Redirect('/login');
        return view('User.code', ['phone' => Session::get('phone')]);
    }

    public function verify(Request $request)
    {
        $phone = Session::get('phone');
        if (is_null($phone)) return redirect('/login');
        if (Cache::missing($phone)) return redirect('/login')->with('status', 'Expired');
        if (Cache::get($phone) != $request->get('code')) return redirect()->back()->with('status', 'Invalid');
        Session::forget('phone');
        Cache::forget($phone);
        Auth::login(User::findByPhone($phone));
        return redirect('/photos');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
