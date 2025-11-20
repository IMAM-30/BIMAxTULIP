@extends('Admin.admin-components.admin-layout')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin-css/admin-kecamatans.css') }}">
<div class="container">
    <h2>Kelola Kecamatan</h2>
    <a href="{{ route('admin.kecamatans.create') }}" class="btn">Tambah Kecamatan</a>

    @if(session('success')) <div class="alert success">{{ session('success') }}</div> @endif

    <table class="table">
        <thead>
            <tr><th>ID</th><th>Nama</th><th>Count</th><th>Order</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->count }}</td>
                <td>{{ $item->order }}</td>
                <td>
                    <a href="{{ route('admin.kecamatans.edit', $item) }}" class="btn small">Edit</a>
                    <form action="{{ route('admin.kecamatans.destroy', $item) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus?')">
                        @csrf @method('DELETE')
                        <button class="btn small danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $items->links() }}
</div>
@endsection
