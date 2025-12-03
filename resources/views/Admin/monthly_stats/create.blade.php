@extends('admin.admin-components.admin-layout')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin-css/admin-kontak.css') }}">

<div class="admin-container">
    <h2>Tambah Statistik Bulanan</h2>

    <form action="{{ route('admin.monthly-stats.store') }}" method="POST">
        @csrf

        <label>Tahun</label>
        <input type="number" name="year" value="{{ old('year', date('Y')) }}" required>

        <label>Bulan (1-12)</label>
        <input type="number" name="month" value="{{ old('month', 1) }}" min="1" max="12" required>

        <label>Label (opsional)</label>
        <input type="text" name="label" value="{{ old('label') }}">

        <label>Value</label>
        <input type="number" name="value" value="{{ old('value', 0) }}" min="0" required>

        <label>Order</label>
        <input type="number" name="order" value="{{ old('order', 0) }}">

        <div style="margin-top:12px;">
            <button class="btn">Simpan</button>
            <a class="btn outline" href="{{ route('admin.monthly-stats.index') }}">Kembali</a>
        </div>
    </form>
</div>
@endsection
