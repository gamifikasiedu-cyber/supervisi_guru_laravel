<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Imports\UsersImport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    // Menampilkan daftar semua user
    public function index()
    {
        $users = User::all();

        return Inertia::render('Users/Index', [
            'users' => $users,
        ]);
    }

    // Menampilkan form tambah user
    public function create()
    {
        return Inertia::render('Users/Create');
    }

    // Menyimpan data user baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'roles' => 'required|array|min:1',
            'roles.*' => 'in:admin,guru,supervisor,kepala_sekolah,pengawas',
            'nip' => 'nullable|string|max:30',
            'mata_pelajaran' => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => User::ROLE_GURU,
            'nip' => $request->nip,
            'mata_pelajaran' => $request->mata_pelajaran,
        ]);

        $user->setRoles($request->roles);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    // Menghapus user dari database
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    // Menampilkan form edit user
    public function edit(User $user)
    {
        return Inertia::render('Users/Edit', [
            'user' => $user,
        ]);
    }

    // Memperbarui data user di database
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'roles' => 'required|array|min:1',
            'roles.*' => 'in:admin,guru,supervisor,kepala_sekolah,pengawas',
            'nip' => 'nullable|string|max:30',
            'mata_pelajaran' => 'nullable|string|max:255',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'nip' => $request->nip,
            'mata_pelajaran' => $request->mata_pelajaran,
        ];

        // Password hanya diubah jika diisi
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:8',
            ]);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        $user->setRoles($request->roles);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    // Mengunduh data seluruh pengguna ke file Excel
    public function export()
    {
        return Excel::download(new UsersExport, 'data-pengguna-'.now()->format('Ymd-His').'.xlsx');
    }

    // Mengimpor data pengguna dari file Excel
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
        ]);

        $extension = strtolower($request->file('file')->getClientOriginalExtension());
        if (! in_array($extension, ['xlsx', 'xls', 'csv'], true)) {
            return redirect()->route('users.index')->with('error', 'Format file tidak valid. Gunakan file .xlsx, .xls, atau .csv.');
        }

        try {
            Excel::import(new UsersImport, $request->file('file'));

            return redirect()->route('users.index')->with('success', 'Data pengguna berhasil diimpor.');
        } catch (\Throwable $e) {
            return redirect()->route('users.index')->with('error', 'Gagal mengimpor data pengguna: '.$e->getMessage());
        }
    }

    // Menghapus semua pengguna kecuali akun yang sedang login
    public function deleteAll()
    {
        User::where('id', '!=', auth()->id())->delete();

        return redirect()->route('users.index')->with('success', 'Semua pengguna berhasil dihapus.');
    }
}
