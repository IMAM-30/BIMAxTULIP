@php
    // fallback jika tidak dikirim dari controller
    $kecamatans = $kecamatans ?? \App\Models\Kecamatan::orderBy('order')->get();
@endphp
<link rel="stylesheet" href="{{ asset('css/user-css/kecamatans.css') }}">
<section class="kecamatan-section">
    <div class="container">
        <h2 class="kecamatan-title">MAPS Laporan Banjir di setiap Kecamatan Kota Parepare {{ date('Y') }}</h2>

        <div class="kecamatan-grid">
            @foreach($kecamatans->take(4) as $k)
            <div class="kec-box">
                <div class="kec-count">{{ $k->count }}</div>
                <div class="kec-name">{{ $k->name }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>
