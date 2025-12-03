<link rel="stylesheet" href="{{ asset('css/user-css/websitekontak.css') }}">

<section class="websitekontak-section reveal">
    <div class="container">
        <h2 class="wk-title reveal">Aplikasi Terkait Bencana</h2>

        <div class="wk-grid">
            @foreach($websitekontaks as $item)
                <a class="wk-card reveal animate-bounce"
                   href="{{ $item->url ?? '#' }}" target="_blank" rel="noopener">

                    <div class="wk-image">
                        @if($item->image)
                            <img src="{{ asset('storage/'.$item->image) }}" alt="{{ $item->name }}">
                        @else
                            <div class="wk-placeholder">{{ $item->name }}</div>
                        @endif
                    </div>

                    <div class="wk-name">{{ $item->name }}</div>
                </a>
            @endforeach
        </div>
    </div>
</section>
