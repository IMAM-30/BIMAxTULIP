@extends('Admin.admin-components.admin-layout')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin-css/admin-pelaporan.css') }}">

<div class="container-admin">
    <h1>Kelola Laporan Banjir</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="table-responsive-wrapper">
        <table class="table-laporan">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>No. Telepon</th>
                    <th>Kecamatan</th>
                    <th>Alamat</th>
                    <th>Link Postingan</th>
                    <th>Ketinggian</th>
                    <th>Waktu Lapor</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($semua_laporan as $laporan)
                    <tr>
                        <td>{{ $laporan->id }}</td>
                        <td>{{ $laporan->nama }}</td>
                        <td>{{ $laporan->no_telepon }}</td>
                        <td>{{ $laporan->kecamatan }}</td>
                        <td>{{ $laporan->alamat }}</td>
                        <td><a href="{{ $laporan->link_postingan }}" target="_blank">Lihat Link</a></td>
                        <td>{{ $laporan->ketinggian_air ?? '-' }}</td>
                        <td>{{ $laporan->created_at->format('d M Y, H:i') }}</td>
                        <td>
                            <form action="/admin/laporan/{{ $laporan->id }}" method="POST" class="form-hapus">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-hapus">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">Belum ada laporan yang masuk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="{{ asset('js/admin-pelaporan.js') }}"></script>
@endsection
