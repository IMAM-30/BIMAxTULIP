@extends('Admin.admin-components.admin-layout')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin-css/admin-kontak.css') }}">

<div class="admin-container">
    <h2>Edit Berita</h2>

    <form action="{{ route('admin.news.update', $news) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <label>Judul</label>
        <input type="text" name="title" value="{{ old('title', $news->title) }}" required>

        <label>Excerpt</label>
        <textarea name="excerpt">{{ old('excerpt', $news->excerpt) }}</textarea>

        <label>Isi Lengkap</label>
        <textarea name="body" rows="8">{{ old('body', $news->body) }}</textarea>

        <label>Tanggal Publikasi</label>
        <input type="date" name="published_at" value="{{ old('published_at', optional($news->published_at)->format('Y-m-d')) }}">

        <label>Gambar saat ini</label>
        @if($news->image)
            <div><img src="{{ asset('storage/'.$news->image) }}" style="height:100px;border-radius:8px"></div>
        @endif

        <label>Ganti Gambar</label>
        <input type="file" name="image" accept="image/*">

        <label>Order</label>
        <input type="number" name="order" value="{{ old('order', $news->order) }}">

        <div style="margin-top:12px;">
            <button class="btn">Update</button>
            <a class="btn outline" href="{{ route('admin.news.index') }}">Kembali</a>
        </div>
    </form>
</div>
@endsection
