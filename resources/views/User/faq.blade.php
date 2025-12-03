@extends('components.layout')

@section('title', 'FAQ')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/user-css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user-css/faq.css') }}">
@endpush


@section('content')
@include('components.hero', ['slides' => $slides])
    <section class="faq-section reveal">
        <div class="faq-container">
            {{-- HEADER + SEARCH --}}
            <div class="faq-header-card reveal animate-bounce">
                <h1 class="faq-title">Pertanyaan yang Sering Diajukan</h1>

                <div class="faq-search-bar">
                    <div class="faq-search-input">
                        <span class="faq-search-icon">🔍</span>
                        <input
                            type="text"
                            id="faqSearch"
                            placeholder="Cari FAQ seputar banjir..."
                        />
                    </div>
                    <button type="button" class="faq-search-btn">
                        Cari
                    </button>
                </div>
            </div>

            {{-- LAYOUT 2 KOLOM --}}
            <div class="faq-layout">
                {{-- SIDEBAR KATEGORI --}}
                <aside class="faq-sidebar reveal">
                    <h2 class="faq-sidebar-title">Kategori FAQ</h2>
                    <div class="faq-filter">
                        <button class="filter-btn active" data-category="all">Semua</button>
                        @foreach($categories as $category)
                            <button class="filter-btn" data-category="{{ $category->id }}">
                                {{ $category->name }}
                            </button>
                        @endforeach
                    </div>
                </aside>

                {{-- DAFTAR FAQ --}}
                <div class="faq-content reveal">
                    <div class="faq-list">
                        @foreach($categories as $category)
                            @foreach($category->faqs as $faq)
                                <div class="faq-item" data-category="{{ $category->id }}">
                                    <button type="button" class="faq-question">
                                        <span class="faq-question-text">{{ $faq->question }}</span>
                                        <span class="faq-cta">
                                            <span class="faq-cta-label">Lihat</span>
                                            <span class="faq-icon">&#9654;</span>
                                        </span>
                                    </button>

                                    <div class="faq-answer">
                                        <p>{{ $faq->answer }}</p>

                                        @if($faq->image1)
                                            <img src="{{ asset('storage/'.$faq->image1) }}" alt="Foto 1">
                                        @endif
                                        @if($faq->image2)
                                            <img src="{{ asset('storage/'.$faq->image2) }}" alt="Foto 2">
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    </div>

                    {{-- TOMBOL LOAD MORE / SHOW LESS --}}
                    <div class="faq-actions">
                        <button type="button" id="faqLoadMore" class="faq-load-more">
                            More
                        </button>
                        <button type="button" id="faqShowLess" class="faq-show-less" style="display:none;">
                            Less
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </section>

    

@endsection
