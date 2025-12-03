@extends('admin.admin-components.admin-layout')

@section('title', 'Daftar WhatsApp')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4">WhatsApp Links</h1>
        <a href="{{ route('admin.whatsapp.create') }}" class="btn btn-primary">Tambah Nomor</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Phone</th>
                <th>Message</th>
                <th>Label</th>
                <th>Active</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($whatsapps as $wa)
                <tr>
                    <td>{{ $wa->id }}</td>
                    <td>{{ $wa->phone }}</td>
                    <td style="max-width:300px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $wa->message }}</td>
                    <td>{{ $wa->label }}</td>
                    <td>{{ $wa->active ? 'YA' : 'TIDAK' }}</td>
                    <td class="d-flex gap-2">
                        <a href="{{ route('admin.whatsapp.edit', $wa) }}" class="btn btn-sm btn-warning">Edit</a>

                        <form action="{{ route('admin.whatsapp.destroy', $wa) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Belum ada nomor WhatsApp.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
