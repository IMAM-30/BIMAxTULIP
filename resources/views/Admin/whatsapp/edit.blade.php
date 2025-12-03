@extends('admin.admin-components.admin-layout')

@section('title', 'Edit WhatsApp')

@section('content')
<div class="container py-4">
    <h1 class="h4 mb-3">Edit Nomor WhatsApp</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.whatsapp.update', $whatsapp) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="phone" class="form-label">Nomor (contoh: +628123456789)</label>
            <input id="phone" name="phone" value="{{ old('phone', $whatsapp->phone) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="message" class="form-label">Pesan default (opsional)</label>
            <input id="message" name="message" value="{{ old('message', $whatsapp->message) }}" class="form-control" placeholder="Halo, saya mau tanya...">
            <small class="text-muted">Pesan akan di-URL encode otomatis saat digunakan.</small>
        </div>

        <div class="mb-3">
            <label for="label" class="form-label">Label (opsional)</label>
            <input id="label" name="label" value="{{ old('label', $whatsapp->label) }}" class="form-control">
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Logo/Ikon (PNG, JPG, SVG) — max 2MB</label>
            <input id="image" type="file" name="image" accept="image/*" class="form-control">
            <small class="form-text text-muted">Upload gambar yang akan ditampilkan di bubble WA.</small>
        </div>

        @if($whatsapp->image)
            <div class="mb-3">
                <label class="form-label">Preview saat ini</label>
                <div>
                    <img id="current-image-preview" src="{{ asset('storage/'.$whatsapp->image) }}" alt="WA logo" style="width:64px;height:64px;object-fit:cover;border-radius:50%;border:1px solid #eee;">
                </div>
            </div>
        @else
            <div class="mb-3" id="no-image-note">
                <small class="text-muted">Belum ada logo di database.</small>
            </div>
        @endif

        <div class="form-check mb-3">
            <input id="active" type="checkbox" name="active" value="1" class="form-check-input" {{ old('active', $whatsapp->active) ? 'checked' : ($whatsapp->active ? 'checked' : '') }}>
            <label for="active" class="form-check-label">Jadikan nomor ini aktif untuk frontend</label>
        </div>

        <button class="btn btn-success">Update</button>
        <a href="{{ route('admin.whatsapp.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var input = document.getElementById('image');
    var preview = document.getElementById('current-image-preview');

    input && input.addEventListener('change', function (e) {
        var file = e.target.files && e.target.files[0];
        if (!file) return;

        // hanya preview gambar
        if (!file.type.startsWith('image/')) {
            alert('Silakan pilih file gambar (PNG/JPG/SVG).');
            input.value = '';
            return;
        }

        var reader = new FileReader();
        reader.onload = function (ev) {
            if (preview) {
                preview.src = ev.target.result;
            } else {
                // buat elemen preview baru jika sebelumnya tidak ada
                var img = document.createElement('img');
                img.id = 'current-image-preview';
                img.style.width = '64px';
                img.style.height = '64px';
                img.style.objectFit = 'cover';
                img.style.borderRadius = '50%';
                img.style.border = '1px solid #eee';
                img.src = ev.target.result;
                var container = input.parentNode;
                container.appendChild(img);
                var note = document.getElementById('no-image-note');
                if (note) note.remove();
            }
        };
        reader.readAsDataURL(file);
    });
});
</script>
@endpush

@endsection
