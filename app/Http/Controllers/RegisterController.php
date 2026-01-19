<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function index(){
        $title = 'Sign Up';
        return view('pages.auth.signup', compact('title'));
    }

    public function store(Request $request){
            $customMessage = [
                'nama' => 'Nama wajib diisi',
                'email.required' => 'Email wajib diisi',
                'email.unique' => 'Email sudah terdaftar',
                'password.required' => 'Password wajib diisi',
                'password.min' => 'Password minimal 5 karakter',
            ];

            $validatedData = $request->validate([
                'nama' => 'required',
                'email' => 'required|unique:users,email',
                'password' => 'required|min:5',
            ], $customMessage);
    
            $validatedData['password'] = Hash::make($validatedData['password']);
    
            User::create($validatedData);
    
            return redirect('signin')->with('registerSuccess', 'Registrasi berhasil! Silakan login.');
        } 
}
