@extends('admin.admin-components.admin-layout')

@section('title', 'Tambah Admin')

@section('content')
<div class="container" style="max-width:720px; margin:32px auto; padding:0 16px;">
    <h2 style="font-size:24px; font-weight:700; margin-bottom:8px;">Tambah Akun Admin</h2>
    <p style="font-size:14px; color:#64748b; margin-bottom:20px;">
        Buat akun admin baru untuk mengelola sistem.
    </p>

    @if(session('success'))
        <div style="background:#dcfce7;color:#166534;padding:8px 10px;border-radius:8px;font-size:13px;margin-bottom:10px;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background:#fee2e2;color:#b91c1c;padding:8px 10px;border-radius:8px;font-size:13px;margin-bottom:10px;">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.accounts.store') }}" method="POST" style="background:#ffffff;padding:24px 20px;border-radius:16px;box-shadow:0 10px 30px rgba(15,23,42,0.08);">
        @csrf

        <div style="margin-bottom:16px;">
            <label for="username" style="display:block;font-size:14px;font-weight:600;color:#0f172a;margin-bottom:6px;">
                Username
            </label>
            <input type="text" name="username" id="username"
                   value="{{ old('username') }}"
                   style="width:100%;border-radius:10px;border:1px solid #e2e8f0;padding:10px 12px;font-size:14px;">
            @error('username')
                <div style="color:#b91c1c;font-size:13px;margin-top:4px;">{{ $message }}</div>
            @enderror
        </div>

        <div style="margin-bottom:16px;">
            <label for="email" style="display:block;font-size:14px;font-weight:600;color:#0f172a;margin-bottom:6px;">
                Email (untuk lupa password)
            </label>
            <input type="email" name="email" id="email"
                   value="{{ old('email') }}"
                   style="width:100%;border-radius:10px;border:1px solid #e2e8f0;padding:10px 12px;font-size:14px;">
            @error('email')
                <div style="color:#b91c1c;font-size:13px;margin-top:4px;">{{ $message }}</div>
            @enderror
        </div>

        <div style="margin-bottom:16px;">
            <label for="password" style="display:block;font-size:14px;font-weight:600;color:#0f172a;margin-bottom:6px;">
                Password
            </label>
            <input type="password" name="password" id="password"
                   style="width:100%;border-radius:10px;border:1px solid #e2e8f0;padding:10px 12px;font-size:14px;">
            @error('password')
                <div style="color:#b91c1c;font-size:13px;margin-top:4px;">{{ $message }}</div>
            @enderror
        </div>

        <div style="margin-bottom:16px;">
            <label for="password_confirmation" style="display:block;font-size:14px;font-weight:600;color:#0f172a;margin-bottom:6px;">
                Konfirmasi Password
            </label>
            <input type="password" name="password_confirmation" id="password_confirmation"
                   style="width:100%;border-radius:10px;border:1px solid #e2e8f0;padding:10px 12px;font-size:14px;">
        </div>

        <div style="display:flex;gap:8px;align-items:center;justify-content:flex-end;margin-top:8px;">
            <a href="{{ route('admin.accounts.index') }}" style="
                padding:8px 14px;border-radius:999px;border:1px solid #cbd5f5;
                font-size:13px;color:#475569;text-decoration:none;background:#f8fafc;
            ">
                Batal
            </a>
            <button type="submit" style="
                padding:8px 16px;border-radius:999px;border:none;
                background:linear-gradient(135deg,#2563eb,#1d4ed8);
                color:#ffffff;font-size:14px;font-weight:600;cursor:pointer;
                box-shadow:0 8px 20px rgba(37,99,235,.35);
            ">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection
