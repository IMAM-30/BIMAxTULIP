<div class="hero-container">
    <div class="hero-slider" id="heroSlider">
        @foreach ($slides as $slide)
            <div class="hero-slide" style="background-image: url('{{ asset('storage/' . $slide->image) }}')">
                <div class="hero-overlay">
                    <div class="hero-content">
                        @if(!empty($slide->date))
                            <p class="hero-date">{{ \Carbon\Carbon::parse($slide->date)->translatedFormat('d F Y') }}</p>
                        @endif
                        <h2>{{ $slide->title }}</h2>
                        <p>{{ $slide->subtitle }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <button class="hero-prev">&#10094;</button>
    <button class="hero-next">&#10095;</button>
</div>
