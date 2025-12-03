@extends('admin.admin-components.admin-layout')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin-css/admin-kecamatans.css') }}">

<div class="container">
    <h2>Tambah Kecamatan</h2>

    <form action="{{ route('admin.kecamatans.store') }}" method="POST">
        @csrf
        <label>Nama</label>
        <input type="text" name="name" required>

        <label>Count</label>
        <input type="number" name="count" min="0" value="0">

        <label>Order</label>
        <input type="number" name="order" min="0" value="0">

        <button type="submit" class="btn">Simpan</button>
    </form>
</div>
@endsection
