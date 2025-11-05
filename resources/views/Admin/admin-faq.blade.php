@extends('Admin.admin-components.admin-layout')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin-css/admin-faq.css') }}">

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="faq-container">
    <h2>Manajemen FAQ</h2>

    @if(session('success'))
        <div class="alert success">{{ session('success') }}</div>
    @endif

    {{-- FORM TAMBAH KATEGORI --}}
    <div class="form-container">
        <form action="{{ route('faq.category.store') }}" method="POST">
            @csrf
            <input type="text" name="name" placeholder="Tambah Kategori Baru..." required>
            <button type="submit">Tambah Kategori</button>
        </form>

        {{-- FORM TAMBAH PERTANYAAN --}}
        <form action="{{ route('faq.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <select name="category_id" required>
                <option value="">Pilih Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
            <input type="text" name="question" placeholder="Pertanyaan..." required>
            <textarea name="answer" placeholder="Jawaban..." required></textarea>
            <input type="file" name="image1" accept="image/*">
            <input type="file" name="image2" accept="image/*">
            <button type="submit">Tambah Pertanyaan</button>
        </form>
    </div>

    {{-- DAFTAR KATEGORI & PERTANYAAN --}}
    @foreach($categories as $category)
        <div class="category-box">
            <div class="category-header">
                <h3 class="category-name">{{ $category->name }}</h3>
                <button class="edit-category-btn">Edit</button>
                <form action="{{ route('faq.category.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="delete-btn">Hapus Kategori</button>
                </form>
            </div>

            {{-- FORM EDIT CATEGORY INLINE --}}
            <form class="category-edit-form" style="display:none;" data-id="{{ $category->id }}">
                @csrf
                @method('PUT')
                <input type="text" name="name" value="{{ $category->name }}" required>
                <div class="category-actions">
                    <button type="button" class="cancel-category-edit-btn">Batal</button>
                    <button type="submit" class="save-category-edit-btn">Simpan</button>
                </div>
            </form>

            @foreach($category->faqs as $faq)
                <div class="faq-item" data-id="{{ $faq->id }}">
                    <div class="faq-view">
                        <h4 class="faq-question">{{ $faq->question }}</h4>
                        <p class="faq-answer">{{ $faq->answer }}</p>

                        <div class="faq-images">
                            @if($faq->image1)
                                <img src="{{ asset('storage/'.$faq->image1) }}" alt="Foto 1">
                            @endif
                            @if($faq->image2)
                                <img src="{{ asset('storage/'.$faq->image2) }}" alt="Foto 2">
                            @endif
                        </div>

                        <div class="faq-actions">
                            <button class="edit-btn">Edit</button>
                            <form action="{{ route('faq.destroy', $faq->id) }}" method="POST" onsubmit="return confirm('Hapus pertanyaan ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="delete-btn">Hapus</button>
                            </form>
                        </div>
                    </div>

                    {{-- FORM EDIT FAQ --}}
                    <form class="faq-edit-form" style="display:none;" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <input type="text" name="question" value="{{ $faq->question }}" required>
                        <textarea name="answer" required>{{ $faq->answer }}</textarea>
                        <input type="file" name="image1" accept="image/*">
                        <input type="file" name="image2" accept="image/*">
                        <div class="faq-actions">
                            <button type="button" class="cancel-edit-btn">Batal</button>
                            <button type="submit" class="save-edit-btn">Simpan</button>
                        </div>
                    </form>
                </div>
            @endforeach
        </div>
    @endforeach
</div>

{{-- SCRIPT UNTUK INLINE EDIT --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // ========== FAQ INLINE EDIT ==========
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const item = this.closest('.faq-item');
            item.querySelector('.faq-view').style.display = 'none';
            item.querySelector('.faq-edit-form').style.display = 'block';
        });
    });

    document.querySelectorAll('.cancel-edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const item = this.closest('.faq-item');
            item.querySelector('.faq-edit-form').style.display = 'none';
            item.querySelector('.faq-view').style.display = 'block';
        });
    });

    document.querySelectorAll('.faq-edit-form').forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const item = this.closest('.faq-item');
            const id = item.dataset.id;
            const formData = new FormData(this);
            formData.append('_method', 'PUT');

            try {
                const response = await fetch(`/admin/faq/${id}`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    body: formData
                });

                const data = await response.json();
                if (response.ok && data.success) {
                    alert('Pertanyaan berhasil diperbarui.');
                    location.reload();
                } else {
                    alert('Terjadi kesalahan saat menyimpan FAQ.');
                    console.error(data);
                }
            } catch(err) {
                alert('Terjadi kesalahan jaringan.');
                console.error(err);
            }
        });
    });

    // ========== CATEGORY INLINE EDIT ==========
    document.querySelectorAll('.edit-category-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const header = this.closest('.category-header');
            header.style.display = 'none';
            const form = header.nextElementSibling;
            form.style.display = 'block';
        });
    });

    document.querySelectorAll('.cancel-category-edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const form = this.closest('.category-edit-form');
            form.style.display = 'none';
            const header = form.previousElementSibling;
            header.style.display = 'flex';
        });
    });

    document.querySelectorAll('.category-edit-form').forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const id = this.dataset.id;
            const formData = new FormData(this);
            formData.append('_method', 'PUT');

            try {
                const response = await fetch(`/admin/faq/category/${id}`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    body: formData
                });

                const data = await response.json();
                if (response.ok && data.success) {
                    alert('Kategori berhasil diperbarui.');
                    location.reload();
                } else {
                    alert('Terjadi kesalahan saat menyimpan kategori.');
                    console.error(data);
                }
            } catch(err) {
                alert('Terjadi kesalahan jaringan.');
                console.error(err);
            }
        });
    });

});
</script>
@endsection
