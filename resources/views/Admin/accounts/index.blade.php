@extends('Admin.admin-components.admin-layout')

@section('title', 'Admin Accounts')

@section('content')
<div class="container" style="padding:32px 16px;">
    <h2 style="margin-bottom:16px;">Daftar Akun Admin</h2>

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

    <a href="{{ route('admin.accounts.create') }}" style="
        display:inline-block;margin-bottom:12px;
        padding:8px 14px;border-radius:999px;background:#2563eb;color:#fff;
        text-decoration:none;font-size:13px;
    ">
        + Tambah Admin
    </a>

    <table border="1" cellpadding="8" cellspacing="0" style="width:100%;font-size:14px;border-collapse:collapse;">
        <thead style="background:#f1f5f9;">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Dibuat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($admins as $admin)
                <tr>
                    <td>{{ $admin->id }}</td>
                    <td>{{ $admin->username }}</td>
                    <td>{{ $admin->email }}</td>
                    <td>{{ $admin->created_at }}</td>
                    <td>
                        <a href="{{ route('admin.accounts.edit', $admin->id) }}">Edit</a>
                        @if(session('admin_id') != $admin->id)
                            |
                            <form action="{{ route('admin.accounts.destroy', $admin->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Hapus akun ini?')">Hapus</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Belum ada akun admin.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
