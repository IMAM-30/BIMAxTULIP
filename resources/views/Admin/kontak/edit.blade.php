@extends('Admin.admin-components.admin-layout')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin-css/admin-kontak.css') }}">

<div class="admin-container">
    <h2>Edit Kontak</h2>

    <form action="{{ route('admin.kontak.update', $kontak) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Title</label>
        <input type="text" name="title" value="{{ old('title', $kontak->title) }}" required>

        <label>Description</label>
        <textarea name="description" rows="4">{{ old('description', $kontak->description) }}</textarea>

        <label>URL</label>
        <input type="text" name="url" value="{{ old('url', $kontak->url) }}">

        <label>Order</label>
        <input type="number" name="order" value="{{ old('order', $kontak->order) }}">

        <button class="btn">Update</button>
        <a class="btn outline" href="{{ route('admin.kontak.index') }}">Kembali</a>
    </form>
</div>
@endsection
