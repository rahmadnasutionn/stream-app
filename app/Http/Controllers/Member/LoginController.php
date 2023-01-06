<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('member.sign_in');
    }

    public function auth(Request $request)
    {
        $request->validate([
            'email' => 'required|email:dns',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        $credentials['role'] = 'member';

        if (Auth::attempt($credentials))
        {
            $request->session()->regenerate();

            return redirect()->route('member.dashboard');
        }

        return back()->withErrors([
            'credentials' => 'Email atau password anda salah'
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('member.sign_in');
    }
}
