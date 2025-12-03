@extends('admin.admin-components.admin-layout')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin-css/admin-kontak.css') }}">

<div class="admin-container">
    <h2>Statistik Bulanan</h2>

    @if(session('success'))
        <div class="alert success">{{ session('success') }}</div>
    @endif

    <a class="btn" href="{{ route('admin.monthly-stats.create') }}">Tambah Data Bulanan</a>

    <table class="admin-table" style="margin-top:12px;">
        <thead>
            <tr><th>#</th><th>Tahun</th><th>Bulan</th><th>Label</th><th>Value</th><th>Order</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->year }}</td>
                <td>{{ $item->month }} — {{ $item->month_name }}</td>
                <td>{{ $item->label }}</td>
                <td>{{ $item->value }}</td>
                <td>{{ $item->order }}</td>
                <td>
                    <a class="btn small" href="{{ route('admin.monthly-stats.edit', $item) }}">Edit</a>

                    <form action="{{ route('admin.monthly-stats.destroy', $item) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus data ini?')">
                        @csrf @method('DELETE')
                        <button class="btn small danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top:12px;">{{ $items->links() }}</div>
</div>
@endsection
