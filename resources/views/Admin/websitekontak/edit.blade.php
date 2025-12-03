@extends('admin.admin-components.admin-layout')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin-css/admin-kontak.css') }}">

<div class="admin-container">
    <h2>Edit Aplikasi</h2>

    <form action="{{ route('admin.websitekontak.update', $websitekontak) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <label>Nama</label>
        <input type="text" name="name" value="{{ old('name', $websitekontak->name) }}" required>

        <label>Gambar sekarang</label>
        @if($websitekontak->image)
            <div><img src="{{ asset('storage/'.$websitekontak->image) }}" style="height:80px"></div>
        @endif
        <label>Ganti Gambar</label>
        <input type="file" name="image" accept="image/*">

        <label>URL</label>
        <input type="text" name="url" value="{{ old('url', $websitekontak->url) }}">

        <label>Order</label>
        <input type="number" name="order" value="{{ old('order', $websitekontak->order) }}">

        <button class="btn">Update</button>
        <a class="btn outline" href="{{ route('admin.websitekontak.index') }}">Kembali</a>
    </form>
</div>
@endsection
