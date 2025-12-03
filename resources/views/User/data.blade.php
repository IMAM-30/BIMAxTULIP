@extends('components.layout')

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

    <section class="monthly-stats-section reveal">
        <div class="container">
            <div class="monthly-stats-card">
                <div class="monthly-stats-header">
                    <div>
                        <p class="monthly-stats-eyebrow">Statistik Laporan</p>
                        <h2 class="monthly-stats-title">
                            Rekap Banjir Kota Parepare {{ $year }}
                        </h2>
                        <p class="monthly-stats-subtitle">
                            Jumlah laporan banjir per bulan dalam satu tahun kalender.
                        </p>
                    </div>

                    <form method="GET" action="{{ route('data') }}" class="monthly-stats-year-form">
                        <label class="year-label" for="yearSelect">Tahun</label>
                        <select id="yearSelect" name="year" onchange="this.form.submit()" class="year-select">
                            @foreach($availableYears as $y)
                                <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <div class="monthly-stats-chart-wrapper">
                    <canvas id="monthlyStatsChart"></canvas>
                </div>
            </div>
        </div>
    </section>

    {{-- Section: Berita / News --}}
    <section class="news-section reveal">
        <div class="container news-container">
            <div class="news-header">
                <div>
                    <p class="news-eyebrow">Update Laporan</p>
                    <h2 class="news-title">Berita & Laporan Terbaru</h2>
                    <p class="news-subtitle">
                        Ringkasan kejadian banjir, respons cepat, dan informasi penting lainnya di Kota Parepare.
                    </p>
                </div>
            </div>

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

                    <article class="news-item"
                             data-index="{{ $globalIndex }}"
                             style="{{ $index >= 3 ? 'display:none;' : '' }}">
                        <div class="news-body">
                            <span class="news-date">
                                @if(!empty($item->published_at))
                                    {{ \Carbon\Carbon::parse($item->published_at)->translatedFormat('l, d F Y') }}
                                @else
                                    -
                                @endif
                            </span>

                            <h3 class="news-heading">{{ $item->title }}</h3>

                            <p class="news-excerpt">
                                {!! \Illuminate\Support\Str::limit($item->excerpt ?? '', 200) !!}
                            </p>

                            <div class="news-full" aria-hidden="true">
                                <p>{!! nl2br(e($item->body ?? '')) !!}</p>
                            </div>

                            <div class="news-actions">
                                <button class="btn-more" type="button" aria-expanded="false">Baca selengkapnya</button>
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
                    <p class="news-empty">Tidak ada berita saat ini.</p>
                @endforelse
            </div>

            {{-- Pagination + Load More --}}
            <div class="news-footer">
                <button id="btnLoadMore" class="btn-load-more">
                    Tampilkan lebih banyak
                </button>

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
<script src="{{ asset('js/news.js') }}" defer></script>
<script src="{{ asset('js/news-loadmore.js') }}" defer></script>

{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function(){
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
                    label: 'Jumlah laporan',
                    data: values,
                    backgroundColor: 'rgba(14, 148, 136, 0.9)',
                    borderColor: 'rgba(255,255,255,0.6)',
                    borderWidth: 1.2,
                    borderRadius: 8,
                    maxBarThickness: 32
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        padding: 10,
                        cornerRadius: 6
                    }
                },
                scales: {
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { color: '#e5e7eb', font: { size: 11 } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(15,23,42,0.18)', drawBorder: false },
                        ticks: { color: '#e5e7eb', font: { size: 11 }, precision:0 }
                    }
                },
                layout: { padding: { top: 6, bottom: 6, left: 4, right: 4 } }
            }
        });
    }
});
</script>
@endpush
