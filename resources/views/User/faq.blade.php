@extends('Components.layout')

@section('title', 'FAQ')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/user-css/home.css') }}">
<link rel="stylesheet" href="{{ asset('css/user-css/faq.css') }}">
@endpush

@section('content')

@include('components.hero', ['slides' => $slides])

<section class="faq-section">
    <div class="container">
        <h1 class="faq-title">Pertanyaan yang Sering Diajukan</h1>

        {{-- Search --}}
        <div class="faq-search">
            <input type="text" id="faqSearch" placeholder="Cari pertanyaan...">
        </div>

        {{-- Filter Kategori --}}
        <div class="faq-filter">
            <button class="filter-btn active" data-category="all">Semua</button>
            @foreach($categories as $category)
                <button class="filter-btn" data-category="{{ $category->id }}">{{ $category->name }}</button>
            @endforeach
        </div>

        {{-- Daftar FAQ --}}
        <div class="faq-list">
            @foreach($categories as $category)
                @foreach($category->faqs as $faq)
                    <div class="faq-item" data-category="{{ $category->id }}">
                        <div class="faq-question">
                            <span>{{ $faq->question }}</span>
                            <span class="faq-icon">&#9654;</span>
                        </div>
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
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const faqItems = document.querySelectorAll('.faq-item');
    const faqQuestions = document.querySelectorAll('.faq-question');
    const filterBtns = document.querySelectorAll('.filter-btn');
    const searchInput = document.getElementById('faqSearch');

    // Accordion
    faqQuestions.forEach(q => {
        q.addEventListener('click', function() {
            const parent = this.parentElement;
            const answer = parent.querySelector('.faq-answer');
            const icon = this.querySelector('.faq-icon');

            const isActive = parent.classList.contains('active');

            faqItems.forEach(item => {
                item.classList.remove('active');
                item.querySelector('.faq-answer').style.maxHeight = null;
                item.querySelector('.faq-icon').style.transform = 'rotate(0deg)';
            });

            if(!isActive){
                parent.classList.add('active');
                answer.style.maxHeight = answer.scrollHeight + 'px';
                icon.style.transform = 'rotate(90deg)';
            }
        });
    });

    // Filter kategori
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const catId = this.dataset.category;

            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            faqItems.forEach(item => {
                if(catId === 'all' || item.dataset.category === catId){
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    // Search FAQ
    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase();
        faqItems.forEach(item => {
            const questionText = item.querySelector('.faq-question span').textContent.toLowerCase();
            if(questionText.includes(query)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
});
</script>
@endsection
