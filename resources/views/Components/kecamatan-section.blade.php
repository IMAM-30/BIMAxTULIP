@php
    $kecamatans = $kecamatans ?? \App\Models\Kecamatan::orderBy('order')->get();
@endphp
<link rel="stylesheet" href="{{ asset('css/user-css/kecamatans.css') }}">

<section class="kecamatan-section reveal">
    <div class="container">
        <h2 class="kecamatan-title reveal">
            LAPORAN BANJIR DI KECAMATAN KOTA PAREPARE {{ date('Y') }}
        </h2>

        <p class="kecamatan-helper-mobile reveal">
            Geser ke samping untuk melihat kecamatan lainnya
        </p>

        <div class="kecamatan-grid">
            @foreach($kecamatans->take(4) as $k)
                <div class="kec-box reveal">
                    <div class="kec-count">{{ $k->count }}</div>
                    <div class="kec-name">{{ $k->name }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>
