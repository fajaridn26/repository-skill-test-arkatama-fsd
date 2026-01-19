<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
  public function index()
  {
    $title = 'Profile';
    $users = User::select(['id', 'nama', 'email', 'kelas', 'angkatan', 'no_whatsapp', 'role'])->where('id', Auth::id())->first();

    return view('pages.profile', compact('users', 'title'));
  }

  public function edit(Request $request, $id)
  {
    $request->validate([
      'nama' => 'required|string|max:255',
      'email' => 'required|email|unique:users,email,' . $id,
      'kelas' => 'required|string|max:100',
      'angkatan' => 'required|integer',
      'no_whatsapp' => 'required|integer',
    ]);

    try {
      $user = User::findOrFail($id);
      $user->update([
        'nama' => $request->nama,
        'email' => $request->email,
        'kelas' => $request->kelas,
        'angkatan' => $request->angkatan,
        'no_whatsapp' => $request->no_whatsapp
      ]);

      return response()->json([
        'success' => true,
        'message' => 'Profile berhasil diperbarui!',
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage()
      ], 422);
    }
  }

  public function changePassword(Request $request)
  {
    $request->validate([
      'passwordLama' => 'required|min:5',
      'passwordBaru' => 'required|min:5',
      'konfirmasiPasswordBaru' => 'required|same:passwordBaru|min:5',
    ], [
      'passwordLama.required' => 'Password tidak boleh kosong',
      'passwordBaru.required' => 'Password baru tidak boleh kosong',
      'konfirmasiPasswordBaru.required' => 'Konfirmasi password baru tidak boleh kosong',
      'konfirmasiPasswordBaru.same' => 'Konfirmasi password baru tidak sama',
    ]);

    $users = User::findOrFail(Auth::id());

    try {
      if (!Hash::check($request->passwordLama, $users->password)) {
        return response()->json([
          'success' => false,
          'message' => 'Password lama salah!'
        ], 400);
      }

      $users->update([
        'password' => Hash::make($request->passwordBaru),
      ]);

      return response()->json([
        'success' => true,
        'message' => 'Password berhasil diperbarui!'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage()
      ], 422);
    }
  }
}
