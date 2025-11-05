@extends('Components.layout')

@section('title', 'Beranda')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/user-css/home.css') }}">
<link rel="stylesheet" href="{{ asset('css/user-css/map-popup.css') }}">
@endpush

@section('content')
    {{-- Hero Section --}}
    @include('components.hero', ['slides' => $slides])

    {{-- Statistik Laporan --}}
    <section class="stats">
        <h2> PELAPORAN Laporan Banjir di setiap Kecamatan Kota Parepare 2025</h2>
        <div class="stats-container">
            <div class="stat-box"><h3>3</h3><p>Bacukiki</p></div>
            <div class="stat-box"><h3>0</h3><p>Bacukiki Barat</p></div>
            <div class="stat-box"><h3>9</h3><p>Soreang</p></div>
            <div class="stat-box"><h3>7</h3><p>Ujung</p></div>
        </div>
    </section>

    {{-- Maps Section --}}
    <section id="lokasi" class="lokasi-section">
        <h2>Lokasi Kejadian Banjir</h2>
        <div id="map" style="height: 450px; border-radius: 10px; overflow: hidden;"></div>

            <div id="mapPopupOverlay" class="map-popup-overlay">
                <div class="map-popup-card">
                    <button class="popup-close" onclick="closeMapPopup()">×</button>
                    <img id="popupMapImage" src="" alt="Gambar Lokasi">
                    <div class="popup-map-info">
                        <h3 id="popupMapNama"></h3>
                        <p id="popupMapAlamat"></p>
                        <p id="popupMapTanggal"></p>
                    </div>
                </div>
            </div>
        </section>
    </section>

    {{-- Leaflet CDN --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    {{-- Script Peta --}}
    <script>
    document.addEventListener('DOMContentLoaded', async () => {
        const map = L.map('map').setView([-4.016, 119.623], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        try {
            const res = await fetch('/api/maps');
            const locations = await res.json();

            if (!Array.isArray(locations) || locations.length === 0) {
                console.log('Tidak ada data titik banjir ditemukan.');
                return;
            }

            locations.forEach(loc => {
                if (!loc.latitude || !loc.longitude) return;

                const marker = L.marker([
                    parseFloat(loc.latitude),
                    parseFloat(loc.longitude)
                ]).addTo(map);

                const popupContent = `
                    <div class="popup-card">
                        <div class="popup-img-container">
                            ${loc.gambar ? `<img src="/storage/${loc.gambar}" alt="${loc.nama_lokasi}" class="popup-img">` : ''}
                            <div class="popup-date">${new Date(loc.tanggal).toLocaleDateString('id-ID', {
                                weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
                            })}</div>
                            <div class="popup-name">${loc.nama_lokasi}</div>
                            <div class="popup-location">${loc.alamat}</div>
                             <!-- ✅ Pindah ke bawah sini -->
                        </div>
                        <div class="popup-info">
                            <div><span>Ketinggian Air</span>: ${loc.ketinggian_air} cm</div>
                            <div><span>Rumah yang terdampak</span>: ${loc.rumah_terdampak} rumah</div>
                            <div><span>Jumlah Korban</span>: ${loc.jumlah_korban}</div>
                            <div><span>Luas Cakupan Banjir</span>: ${loc.luas_cakupan} m²</div>
                        </div>
                    </div>
                `;



                marker.bindPopup(popupContent);
            });
        } catch (error) {
            console.error('Gagal memuat data peta:', error);
        }
    });
    </script>

    


@endsection

@push('scripts')
<script src="{{ asset('js/hero.js') }}" defer></script>
@endpush
