@extends('Admin.admin-components.admin-layout')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin-css/admin-kontak.css') }}">

<div class="admin-container">
    <h2>Tambah Aplikasi</h2>

    <form action="{{ route('admin.websitekontak.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label>Nama</label>
        <input type="text" name="name" value="{{ old('name') }}" required>

        <label>Gambar (jpg/png/webp)</label>
        <input type="file" name="image" accept="image/*">

        <label>URL</label>
        <input type="text" name="url" value="{{ old('url') }}">

        <label>Order</label>
        <input type="number" name="order" value="{{ old('order', 0) }}">

        <button class="btn">Simpan</button>
        <a class="btn outline" href="{{ route('admin.websitekontak.index') }}">Kembali</a>
    </form>
</div>
@endsection
