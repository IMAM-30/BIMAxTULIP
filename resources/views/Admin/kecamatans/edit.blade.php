@extends('Admin.admin-components.admin-layout')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin-css/admin-kecamatans.css') }}">

<div class="container">
    <h2>Edit Kecamatan</h2>

    <form action="{{ route('admin.kecamatans.update', $kecamatan) }}" method="POST">
        @csrf @method('PUT')
        <label>Nama</label>
        <input type="text" name="name" value="{{ old('name',$kecamatan->name) }}" required>

        <label>Count</label>
        <input type="number" name="count" min="0" value="{{ old('count',$kecamatan->count) }}">

        <label>Order</label>
        <input type="number" name="order" min="0" value="{{ old('order',$kecamatan->order) }}">

        <button type="submit" class="btn">Simpan</button>
    </form>
</div>
@endsection
