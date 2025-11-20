@extends('Admin.admin-components.admin-layout')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin-css/admin-kontak.css') }}">

<div class="admin-container">
    <h2>Tambah Kontak</h2>

    <form action="{{ route('admin.kontak.store') }}" method="POST">
        @csrf

        <label>Title</label>
        <input type="text" name="title" value="{{ old('title') }}" required>

        <label>Description</label>
        <textarea name="description" rows="4">{{ old('description') }}</textarea>

        <label>URL</label>
        <input type="text" name="url" value="{{ old('url') }}">

        <label>Order</label>
        <input type="number" name="order" value="{{ old('order', 0) }}">

        <button class="btn">Simpan</button>
        <a class="btn outline" href="{{ route('admin.kontak.index') }}">Kembali</a>
    </form>
</div>
@endsection
