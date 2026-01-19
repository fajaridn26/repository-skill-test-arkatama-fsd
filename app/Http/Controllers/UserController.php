<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\LoginUserRequest;
use App\Http\Requests\User\RegisterUserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function showRegisterForm()
    {
        return view('pages.auth.signup');
    }
    public function showLoginForm()
    {
        return view('pages.auth.signin');
    }

    public function signUp(RegisterUserRequest $request)
    {
        $validatedData = $request->validated();

        $this->userService->signUp($validatedData);

        return redirect('signin')->with('registerSuccess', 'Registrasi berhasil! Silakan login.');
    }

    public function signIn(LoginUserRequest $request)
    {
        $validatedData = $request->validated();

        if ($this->userService->signIn($validatedData)) {

            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()->with('loginError', 'Login gagal!');
    }

    public function signOut(Request $request)
    {
        $this->userService->signOut();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('signin');
    }

    // public function index(Request $request)
    // {
    //     $perPage = 10;
    //     $title = 'User';

    //     $users = User::select([
    //         'id',
    //         'nama',
    //         'email',
    //         'kelas',
    //         'angkatan',
    //         'no_whatsapp',
    //         'role',
    //     ])->where('role', 'Siswa')
    //         ->orderBy('id', 'desc')
    //         ->paginate($perPage);

    //     if ($request->ajax()) {
    //         return response()->json($users);
    //     }

    //     return view('pages.tables.user', compact('users', 'title'));
    // }


    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'nama' => 'required|string|max:255',
    //         'email' => 'required|email|unique:users,email',
    //         'kelas' => 'required|string|max:100',
    //         'angkatan' => 'required|integer',
    //     ]);

    //     try {
    //         User::create([
    //             'nama' => $request->nama,
    //             'email' => $request->email,
    //             'kelas' => $request->kelas,
    //             'angkatan' => $request->angkatan,
    //             'password' => Hash::make('12345'),
    //         ]);
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'User berhasil ditambahkan!',
    //         ]);
    //     } catch (\Throwable $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => $e->getMessage()
    //         ], 422);
    //     }
    // }

    // public function edit(Request $request, $id)
    // {
    //     $request->validate([
    //         'nama' => 'required|string|max:255',
    //         'email' => 'required|email|unique:users,email,' . $id,
    //         'kelas' => 'required|string|max:100',
    //         'angkatan' => 'required|integer',
    //     ]);

    //     $user = User::findOrFail($id);
    //     $user->update([
    //         'nama' => $request->nama,
    //         'email' => $request->email,
    //         'kelas' => $request->kelas,
    //         'angkatan' => $request->angkatan,
    //     ]);

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'User berhasil diperbarui!',
    //     ]);
    // }

    // public function resetPassword($id)
    // {
    //     $user = User::findOrFail($id);
    //     $user->update([
    //         'password' => Hash::make('12345')
    //     ]);

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Password user berhasil direset!',
    //     ]);
    // }

    // public function search(Request $request)
    // {
    //     $query = $request->input('query');

    //     if (!$query) {
    //         return response()->json([]);
    //     }

    //     $users = User::whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($query) . '%'])->where('role', 'Siswa')->paginate(10);

    //     return response()->json($users);
    // }

    // public function importExcel(Request $request)
    // {
    //     $request->validate([
    //         'importFile' => 'required|mimes:xlsx'
    //     ]);

    //     try {
    //         Excel::import(new UsersImport, request()->file('importFile'));

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'User berhasil ditambahkan melalui import Excel!'
    //         ]);
    //     } catch (\Throwable $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => $e->getMessage()
    //         ], 422);
    //     }
    // }

    // public function destroy($id)
    // {
    //     $user = User::findOrFail($id);

    //     $user->delete();

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'User berhasil dihapus!'
    //     ]);
    // }

    //     public function importExcel(Request $request)
    // {
    //     $request->validate([
    //         'importFile' => 'required|mimes:xlsx'
    //     ]);

    //     $import = new UsersImport();
    //     Excel::import($import, $request->file('importFile'));

    //     if ($import->failures()->isNotEmpty()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Terdapat data duplikat atau tidak valid',
    //             'errors'  => $import->failures()
    //         ], 422);
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'User berhasil ditambahkan melalui import Excel!'
    //     ]);
    // }

}
