<link rel="stylesheet" href="{{ asset('css/user-css/kontak.css') }}">

<section class="kontak-section reveal">
    <div class="container">
        <h2 class="kontak-section-title reveal">Kontak & Layanan</h2>

        <div class="kontak-grid">
            @foreach($kontaks->chunk(3) as $chunk)
                <div class="kontak-row">
                    @foreach($chunk as $kontak)
                        <div class="kontak-card reveal animate-bounce">
                            <h3 class="kontak-title">{{ $kontak->title }}</h3>
                            <p class="kontak-desc">{!! nl2br(e($kontak->description)) !!}</p>
                            @if($kontak->url)
                                <a class="kontak-link" href="{{ $kontak->url }}" target="_blank" rel="noopener">
                                    {{ $kontak->url }}
                                </a>
                            @endif
                        </div>
                    @endforeach

                    @if($chunk->count() < 3)
                        @for($i = 0; $i < 3 - $chunk->count(); $i++)
                            <div class="kontak-card empty"></div>
                        @endfor
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>
