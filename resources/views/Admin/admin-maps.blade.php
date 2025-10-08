@extends('Admin.admin-components.admin-layout')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

<div class="admin-maps-container">
    <h2>Manajemen Data Titik Banjir</h2>

    {{-- FORM TAMBAH / EDIT --}}
    <form id="mapForm" enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="id" name="id">

        <div class="form-group">
            <label for="nama_lokasi">Nama Lokasi</label>
            <input type="text" id="nama_lokasi" name="nama_lokasi" required>
        </div>

        <div class="form-group">
            <label for="tanggal">Tanggal Kejadian</label>
            <input type="date" id="tanggal" name="tanggal" required>
        </div>

        <div class="form-group">
            <label for="alamat">Alamat</label>
            <input type="text" id="alamat" name="alamat" required>
        </div>

        <div class="form-group">
            <label for="ketinggian_air">Ketinggian Air (cm)</label>
            <input type="number" id="ketinggian_air" name="ketinggian_air" required>
        </div>

        <div class="form-group">
            <label for="rumah_terdampak">Jumlah Rumah Terdampak</label>
            <input type="number" id="rumah_terdampak" name="rumah_terdampak" required>
        </div>

        <div class="form-group">
            <label for="jumlah_korban">Jumlah Korban</label>
            <input type="number" id="jumlah_korban" name="jumlah_korban" required>
        </div>

        <div class="form-group">
            <label for="luas_cakupan">Luas Cakupan (m²)</label>
            <input type="number" id="luas_cakupan" name="luas_cakupan" required>
        </div>

        <div class="form-group">
            <label for="latitude">Latitude</label>
            <input type="text" id="latitude" name="latitude" required>
        </div>

        <div class="form-group">
            <label for="longitude">Longitude</label>
            <input type="text" id="longitude" name="longitude" required>
        </div>

        <div class="form-group">
            <label for="gambar">Gambar (opsional)</label>
            <input type="file" id="gambar" name="gambar" accept="image/*">
        </div>

        <div id="map" style="height:300px; margin:10px 0; border-radius:8px;"></div>

        <div class="button-container">
            {{-- Tombol Simpan Baru --}}
            <button type="button" id="btnAdd" class="btn btn-success">Simpan Baru</button>
            {{-- Tombol Edit --}}
            <button type="submit" id="btnSubmit" class="btn btn-primary" style="display:none;">Simpan Perubahan</button>
            <button type="button" id="btnCancel" class="btn btn-secondary" style="display:none;">Batal</button>
        </div>
    </form>

    {{-- TABEL DATA --}}
    <h3 style="margin-top:30px;">Daftar Titik Banjir</h3>
    <table class="admin-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Lokasi</th>
                <th>Tanggal</th>
                <th>Alamat</th>
                <th>Ketinggian</th>
                <th>Rumah</th>
                <th>Korban</th>
                <th>Luas</th>
                <th>Koordinat</th>
                <th>Gambar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="mapTableBody">
            @if($maps->isEmpty())
                <tr>
                    <td colspan="11" style="text-align:center; color:gray;">Belum ada data titik banjir</td>
                </tr>
            @else
                @foreach ($maps as $index => $map)
                    <tr data-id="{{ $map->id }}">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $map->nama_lokasi }}</td>
                        <td>{{ $map->tanggal }}</td>
                        <td>{{ $map->alamat }}</td>
                        <td>{{ $map->ketinggian_air }} cm</td>
                        <td>{{ $map->rumah_terdampak }}</td>
                        <td>{{ $map->jumlah_korban }}</td>
                        <td>{{ $map->luas_cakupan }} m²</td>
                        <td>{{ $map->latitude }}, {{ $map->longitude }}</td>
                        <td>
                            @if($map->gambar)
                                <img src="{{ asset('storage/'.$map->gambar) }}" alt="gambar" class="table-img" style="width:70px;height:70px;object-fit:cover;border-radius:5px;">
                            @else
                                <span>-</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn-edit" data-id="{{ $map->id }}">Edit</button>
                            <button class="btn-delete" data-id="{{ $map->id }}">Hapus</button>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="{{ asset('js/admin-maps.js') }}"></script>
@endsection
