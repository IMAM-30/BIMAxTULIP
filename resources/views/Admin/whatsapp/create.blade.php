@extends('admin.admin-components.admin-layout')

@section('content')
<div class="container py-4">
    <h1 class="h4 mb-3">Tambah Nomor WhatsApp</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.whatsapp.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="phone" class="form-label">Nomor (contoh: +628123456789)</label>
            <input id="phone" name="phone" value="{{ old('phone') }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="message" class="form-label">Pesan default (opsional)</label>
            <input id="message" name="message" value="{{ old('message') }}" class="form-control" placeholder="Halo, saya mau tanya...">
            <small class="text-muted">Pesan akan di-URL encode otomatis saat digunakan.</small>
        </div>
        
        <div class="mb-3">
            <label for="image" class="form-label">Logo (PNG/JPG/SVG) — max 2MB</label>
            <input id="image" type="file" name="image" accept="image/*" class="form-control">
        </div>
        
        <div class="mb-3">
            <label for="label" class="form-label">Label (opsional)</label>
            <input id="label" name="label" value="{{ old('label') }}" class="form-control" placeholder="Contoh: Hubungi via WA">
        </div>

        <div class="form-check mb-3">
            <input id="active" type="checkbox" name="active" value="1" class="form-check-input" {{ old('active') ? 'checked' : '' }}>
            <label for="active" class="form-check-label">Jadikan nomor ini aktif untuk frontend</label>
        </div>

        <button class="btn btn-success">Simpan</button>
        <a href="{{ route('admin.whatsapp.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
