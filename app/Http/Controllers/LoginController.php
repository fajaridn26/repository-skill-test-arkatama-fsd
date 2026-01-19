<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index()
    {
        $title = "Sign In";
        return view('pages.auth.signin', compact('title'));
    }

    public function authenticate(Request $request)
    {

        $customMessage = [
            'email.required' => 'Email wajib diisi',
            'password.required' => 'Password wajib diisi',
        ];

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:5',
        ], $customMessage);

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()->with('loginError', 'Login gagal!');
    }

    public function signout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('signin');
    }
}
