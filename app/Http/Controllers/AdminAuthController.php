<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $admin = Admin::where('username', $request->username)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return back()->withErrors([
                'username' => 'Username atau password salah.',
            ])->withInput();
        }

        // Simpan informasi di session
        session([
            'admin_id' => $admin->id,
            'admin_username' => $admin->username,
        ]);

        return redirect()->route('admin.home');
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['admin_id', 'admin_username']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Balik ke home user atau ke halaman login admin
        return redirect()->route('home.index')->with('success', 'Anda berhasil logout.');
    }
}
