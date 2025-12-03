@extends('components.layout')

@section('title', 'Admin Login')

@push('styles')
<style>
    .admin-login-wrapper {
        min-height: 60vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f7f7fb;
        padding: 40px 16px;
    }
    .admin-login-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 32px 28px;
        max-width: 420px;
        width: 100%;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.12);
    }
    .admin-login-card h2 {
        margin-bottom: 8px;
        font-size: 24px;
        color: #0f172a;
        font-weight: 700;
        text-align: center;
    }
    .admin-login-card p {
        margin-bottom: 24px;
        font-size: 14px;
        color: #64748b;
        text-align: center;
    }
    .admin-login-card label {
        font-size: 14px;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 6px;
        display: block;
    }
    .admin-login-card input {
        width: 100%;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 10px 12px;
        font-size: 14px;
        outline: none;
        transition: border-color .2s, box-shadow .2s;
    }
    .admin-login-card input:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 2px rgba(37,99,235,.15);
    }
    .admin-login-card button {
        width: 100%;
        margin-top: 18px;
        border-radius: 999px;
        border: none;
        padding: 10px 16px;
        font-size: 15px;
        font-weight: 600;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #ffffff;
        cursor: pointer;
        transition: transform .1s, box-shadow .1s;
        box-shadow: 0 8px 20px rgba(37,99,235,.35);
    }
    .admin-login-card button:hover {
        transform: translateY(-1px);
        box-shadow: 0 12px 26px rgba(37,99,235,.4);
    }
    .admin-login-card .error-text {
        color: #b91c1c;
        font-size: 13px;
        margin-top: 4px;
    }
    .admin-login-card .link-row {
        margin-top: 12px;
        font-size: 13px;
        text-align: right;
    }
    .admin-login-card .link-row a {
        color: #2563eb;
        text-decoration: none;
    }
</style>
@endpush

@section('content')
<div class="admin-login-wrapper">
    <div class="admin-login-card">
        <h2>Admin Panel</h2>
        <p>Silakan login untuk mengelola sistem.</p>

        @if (session('error'))
            <div style="background:#fee2e2;color:#b91c1c;padding:8px 10px;border-radius:8px;font-size:13px;margin-bottom:10px;">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST">
            @csrf
            <div style="margin-bottom:16px;">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" value="{{ old('username') }}">
                @error('username')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom:8px;">
                <label for="password">Password</label>
                <input type="password" name="password" id="password">
                @error('password')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="link-row">
                {{-- Nanti bisa dihubungkan ke fitur lupa password --}}
                <a href="#">Lupa password?</a>
            </div>

            <button type="submit">Login Admin</button>
        </form>
    </div>
</div>
@endsection
