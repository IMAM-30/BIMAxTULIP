@extends('Components.layout')

@section('title', 'Data Laporan Banjir')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/user-css/home.css') }}">
<link rel="stylesheet" href="{{ asset('css/user-css/map-popup.css') }}">
<link rel="stylesheet" href="{{ asset('css/user-css/news.css') }}">
<link rel="stylesheet" href="{{ asset('css/user-css/data.css') }}">
@endpush

@section('content')
    {{-- Hero Section --}}
    @include('components.hero', ['slides' => $slides])

    {{-- Statistik Bulanan --}}
@php
    $monthlyStats = $monthlyStats ?? collect();
    $year = $monthlyStats->first()->year ?? date('Y');

    $months = collect(range(1,12))->map(function($m) use ($monthlyStats, $year){
        $stat = $monthlyStats->firstWhere('month', $m);
        return (object) [
            'month' => $m,
            'label' => $stat->label ?? \Carbon\Carbon::createFromDate($year,$m,1)->translatedFormat('F'),
            'value' => $stat->value ?? 0,
        ];
    });
@endphp

<section class="monthly-stats-section" style="margin-top:28px;">
    <div class="container">

    <div style="text-align:right; margin-bottom:10px;">
        <form method="GET" action="{{ route('data') }}">
            <select name="year" onchange="this.form.submit()" class="year-select">
                @foreach($availableYears as $y)
                    <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>


        <h2 class="news-title">Statistik Banjir di setiap Kecamatan Kota Parepare {{ $year }}</h2>

        <div style="height:260px;">
            <canvas id="monthlyStatsChart" height="140" style="width:100%"></canvas>
        </div>
    </div>
</section>

    {{-- Section: Berita / News --}}
    <section class="news-section" style="margin-top:28px;">
        <div class="container">
            <h2 class="news-title">Berita & Laporan Terbaru</h2>

            @php
                // jika $news adalah paginator, buat startIndex agar index global konsisten
                if (isset($news) && method_exists($news, 'currentPage')) {
                    $startIndex = ($news->currentPage() - 1) * $news->perPage();
                } else {
                    $startIndex = 0;
                }
            @endphp

            <div class="news-list" id="newsList">
                @forelse($news as $index => $item)
                    @php $globalIndex = $startIndex + $index; @endphp

                    <article class="news-item" data-index="{{ $globalIndex }}">
                        <div class="news-body">
                            <div class="news-meta">
                                <span class="news-date">
                                    @if(!empty($item->published_at))
                                        {{ \Carbon\Carbon::parse($item->published_at)->translatedFormat('l, d F Y') }}
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>

                            <h3 class="news-heading">{{ $item->title }}</h3>

                            <p class="news-excerpt">
                                {!! \Illuminate\Support\Str::limit($item->excerpt ?? '', 200) !!}
                            </p>

                            <div class="news-full" aria-hidden="true">
                                <p>{!! nl2br(e($item->body ?? '')) !!}</p>
                            </div>

                            <div class="news-actions">
                                <button class="btn-more" type="button" aria-expanded="false">More</button>
                            </div>
                        </div>

                        <div class="news-media">
                            <img class="news-image"
                                src="{{ $item->image && \Illuminate\Support\Str::startsWith($item->image, ['http','https']) ? $item->image : ($item->image ? asset('storage/'.$item->image) : asset('images/placeholder-800x500.png')) }}"
                                alt="{{ $item->title }}"
                                loading="lazy"
                                onerror="this.onerror=null;this.src='{{ asset('images/placeholder-800x500.png') }}';"
                            />
                        </div>
                    </article>
                @empty
                    <p>Tidak ada berita saat ini.</p>
                @endforelse
            </div>

            {{-- Pagination (Laravel) --}}
            <div class="news-footer" style="margin-top:18px;">
                @if(isset($news) && method_exists($news, 'links'))
                    <div class="pagination-wrapper">
                        {{ $news->links() }}
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script src="{{ asset('js/hero.js') }}" defer></script>
<script src="{{ asset('js/news.js') }}" defer></script> <!-- untuk tombol More, modal gambar, dsb -->
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function(){
    // Chart: monthly stats
    const months = {!! json_encode($months->pluck('label')) !!};
    const values = {!! json_encode($months->pluck('value')) !!};
    const ctxEl = document.getElementById('monthlyStatsChart');
    if (ctxEl) {
        const ctx = ctxEl.getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Laporan',
                    data: values,
                    backgroundColor: Array(12).fill('rgba(14,84,44,0.85)'),
                    borderColor: Array(12).fill('rgba(255,255,255,0.2)'),
                    borderWidth: 1,
                    barThickness: 'flex',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false, drawBorder: false }, ticks: { color: '#333' } },
                    y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.06)' }, ticks: { color: '#333' } }
                },
                layout: { padding: { top: 8, bottom: 8 } }
            }
        });
    }

    // small enhancement: collapse/expand "More" per item (news.js may already do this; keep safe fallback)
    document.querySelectorAll('.btn-more').forEach(btn => {
        btn.addEventListener('click', function () {
            const article = this.closest('.news-item');
            if (!article) return;
            const expanded = article.classList.toggle('expanded');
            this.setAttribute('aria-expanded', expanded ? 'true' : 'false');
            if (expanded) article.scrollIntoView({ behavior: 'smooth', block: 'center' });
        });
    });
});
</script>
@endpush
