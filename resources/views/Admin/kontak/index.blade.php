@extends('admin.admin-components.admin-layout')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin-css/admin-kontak.css') }}">

<div class="admin-container">
    <h2>Manajemen Kontak</h2>

    @if(session('success'))
        <div class="alert success">{{ session('success') }}</div>
    @endif

    <div class="top-actions">
        <a href="{{ route('admin.kontak.create') }}" class="btn">Tambah Kontak</a>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Description</th>
                <th>URL</th>
                <th>Order</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->title }}</td>
                <td style="max-width:300px;">{{ \Illuminate\Support\Str::limit($item->description,120) }}</td>
                <td><a href="{{ $item->url }}" target="_blank">{{ $item->url }}</a></td>
                <td>{{ $item->order }}</td>
                <td>
                    <a class="btn small" href="{{ route('admin.kontak.edit', $item) }}">Edit</a>
                    <form action="{{ route('admin.kontak.destroy', $item) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Hapus item ini?')">
                        @csrf @method('DELETE')
                        <button class="btn small danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="pagination">
        {{ $items->links() }}
    </div>
</div>
@endsection
