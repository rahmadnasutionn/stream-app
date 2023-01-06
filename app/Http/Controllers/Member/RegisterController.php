<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function index()
    {
        return view('member.sign_up');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3|max:25',
            'email' => 'required|email',
            'phone_number' => 'required|string',
            'password' => 'required|min:6|max:22',
            'avatar' => 'string',
        ]);

        $data = $request->except('_token');

        $isEmailExist = User::where('email', $request->email)->exists();

        if ($isEmailExist) {
            return back()->withErrors([
                'email' => 'This email already exist'
            ])->withInput();
        }

        $data['avatar'] = '';
        $data['role'] = 'member';
        $data['password'] = Hash::make($request->password);

        User::create($data);

        return redirect()->route('member.sign_in');

    }
}
