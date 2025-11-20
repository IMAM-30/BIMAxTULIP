@extends('Admin.admin-components.admin-layout')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin-css/admin-kontak.css') }}">

<div class="admin-container">
    <h2>Tambah Berita</h2>

    <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <label>Judul</label>
        <input type="text" name="title" value="{{ old('title') }}" required>

        <label>Excerpt</label>
        <textarea name="excerpt">{{ old('excerpt') }}</textarea>

        <label>Isi Lengkap</label>
        <textarea name="body" rows="8">{{ old('body') }}</textarea>

        <label>Tanggal Publikasi</label>
        <input type="date" name="published_at" value="{{ old('published_at') }}">

        <label>Gambar</label>
        <input type="file" name="image" accept="image/*">

        <label>Order</label>
        <input type="number" name="order" value="{{ old('order', 0) }}">

        <div style="margin-top:12px;">
            <button class="btn">Simpan</button>
            <a class="btn outline" href="{{ route('admin.news.index') }}">Kembali</a>
        </div>
    </form>
</div>
@endsection
