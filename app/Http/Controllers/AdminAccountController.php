<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAccountController extends Controller
{
    public function index()
    {
        $admins = Admin::orderBy('id', 'desc')->get();
        return view('admin.accounts.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.accounts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:admins,username',
            'email'    => 'required|email|unique:admins,email',
            'password' => 'required|min:6|confirmed',
        ]);

        Admin::create([
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.accounts.index')
            ->with('success', 'Akun admin berhasil dibuat.');
    }

    public function edit(Admin $account)
    {
        return view('admin.accounts.edit', ['admin' => $account]);
    }

    public function update(Request $request, Admin $account)
    {
        $request->validate([
            'username' => 'required|unique:admins,username,' . $account->id,
            'email'    => 'required|email|unique:admins,email,' . $account->id,
            'password' => 'nullable|min:6|confirmed',
        ]);

        $data = [
            'username' => $request->username,
            'email'    => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $account->update($data);

        return redirect()->route('admin.accounts.index')
            ->with('success', 'Akun admin berhasil diupdate.');
    }

    public function destroy(Admin $account)
    {
        // Optional: larang hapus dirinya sendiri
        if (session('admin_id') == $account->id) {
            return back()->with('error', 'Tidak bisa menghapus akun yang sedang digunakan.');
        }

        $account->delete();

        return back()->with('success', 'Akun admin berhasil dihapus.');
    }
}
