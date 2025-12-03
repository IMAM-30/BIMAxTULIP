@extends('admin.admin-components.admin-layout')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin-css/admin-kontak.css') }}">

<div class="admin-container">
    <h2>Manajemen Aplikasi Terkait</h2>

    @if(session('success'))
        <div class="alert success">{{ session('success') }}</div>
    @endif

    <div class="top-actions">
        <a class="btn" href="{{ route('admin.websitekontak.create') }}">Tambah</a>
    </div>

    <table class="admin-table">
        <thead>
            <tr><th>#</th><th>Nama</th><th>Gambar</th><th>URL</th><th>Order</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->name }}</td>
                <td>
                    @if($item->image)
                        <img src="{{ asset('storage/'.$item->image) }}" alt="" style="height:40px;">
                    @endif
                </td>
                <td><a href="{{ $item->url }}" target="_blank">{{ $item->url }}</a></td>
                <td>{{ $item->order }}</td>
                <td>
                    <a class="btn small" href="{{ route('admin.websitekontak.edit', $item) }}">Edit</a>
                    <form action="{{ route('admin.websitekontak.destroy', $item) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus item?')">
                        @csrf @method('DELETE')
                        <button class="btn small danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="pagination">{{ $items->links() }}</div>
</div>
@endsection
