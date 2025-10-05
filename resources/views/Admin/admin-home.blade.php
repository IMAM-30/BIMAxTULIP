@extends('Admin.admin-components.admin-layout')

@section('title','Dashboard Admin')

@section('custom-css')
<link rel="stylesheet" href="{{ asset('css/admin-css/admin-home.css') }}">
@endsection

@section('content')
<h1>Dashboard Admin - Slide</h1>

@if(session('success'))
<div class="success-message">{{ session('success') }}</div>
@endif

{{-- Form tambah slide --}}
<h2>Tambah Slide Baru</h2>
<form action="{{ route('slides.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div>
        <label>Judul</label>
        <input type="text" name="title" required>
    </div>
    <div>
        <label>Subjudul</label>
        <input type="text" name="subtitle">
    </div>
    <div>
        <label>Tanggal</label>
        <input type="date" name="date" required>
    </div>
    <div>
        <label>Gambar</label>
        <input type="file" name="image" required>
    </div>
    <button type="submit">Tambah Slide</button>
</form>

{{-- Daftar slide --}}
<h2>Daftar Slide</h2>
<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Judul</th>
            <th>Subjudul</th>
            <th>Gambar</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($slides as $slide)
        <tr data-id="{{ $slide->id }}">
            <td class="date">{{ $slide->date->format('Y-m-d') }}</td>
            <td class="title">{{ $slide->title }}</td>
            <td class="subtitle">{{ $slide->subtitle }}</td>
            <td class="image">
                <img src="{{ asset('storage/' . $slide->image) }}" style="max-width:100px;">
            </td>
            <td class="actions">
                <button class="edit-btn">Edit</button>
                <form action="{{ route('slides.destroy', $slide->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Yakin hapus?')">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.edit-btn').forEach(editBtn => {
        editBtn.addEventListener('click', () => {
            const row = editBtn.closest('tr');
            if(row.classList.contains('editing')) return;
            row.classList.add('editing');

            const id = row.dataset.id;
            const dateTd = row.querySelector('.date');
            const titleTd = row.querySelector('.title');
            const subtitleTd = row.querySelector('.subtitle');
            const imageTd = row.querySelector('.image');

            const oldDate = dateTd.textContent;
            const oldTitle = titleTd.textContent;
            const oldSubtitle = subtitleTd.textContent;
            const oldImageSrc = imageTd.querySelector('img').src;

            dateTd.innerHTML = `<input type="date" value="${oldDate}">`;
            titleTd.innerHTML = `<input type="text" value="${oldTitle}">`;
            subtitleTd.innerHTML = `<input type="text" value="${oldSubtitle}">`;
            imageTd.innerHTML = `<img src="${oldImageSrc}" style="max-width:100px;"><br><input type="file">`;

            const actionsTd = row.querySelector('.actions');
            actionsTd.innerHTML = `
                <button class="save-btn">Simpan</button>
                <button class="cancel-btn">Batal</button>
            `;

            // Cancel
            actionsTd.querySelector('.cancel-btn').addEventListener('click', () => {
                dateTd.textContent = oldDate;
                titleTd.textContent = oldTitle;
                subtitleTd.textContent = oldSubtitle;
                imageTd.innerHTML = `<img src="${oldImageSrc}" style="max-width:100px;">`;
                actionsTd.innerHTML = `
                    <button class="edit-btn">Edit</button>
                    <form action="/admin/slides/${id}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Yakin hapus?')">Hapus</button>
                    </form>
                `;
            });

            // Save
            actionsTd.querySelector('.save-btn').addEventListener('click', () => {
                const formData = new FormData();
                formData.append('_method','PUT');
                formData.append('_token','{{ csrf_token() }}');
                formData.append('date', dateTd.querySelector('input').value);
                formData.append('title', titleTd.querySelector('input').value);
                formData.append('subtitle', subtitleTd.querySelector('input').value);

                const fileInput = imageTd.querySelector('input[type="file"]');
                if(fileInput && fileInput.files[0]) formData.append('image', fileInput.files[0]);

                fetch(`/admin/slides/${id}`, {
                    method: 'POST',
                    body: formData
                }).then(res=>{
                    if(res.ok) location.reload();
                    else alert('Gagal update!');
                });
            });
        });
    });
});
</script>
@endsection
