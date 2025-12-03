@extends('admin.admin-components.admin-layout')

@section('title','Dashboard Admin')

@section('custom-css')
<link rel="stylesheet" href="{{ asset('css/admin-css/admin-home.css') }}">
@endsection

@section('content')

<h1>Dashboard Admin</h1>

@if(session('success'))
<div class="success-message">{{ session('success') }}</div>
@endif

{{-- SLIDE --}}
<section class="admin-card">
    <h2>Kelola Slide</h2>

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
            <tr id="slide-{{ $slide->id }}">
                <td class="date">{{ $slide->date->format('Y-m-d') }}</td>
                <td class="title">{{ $slide->title }}</td>
                <td class="subtitle">{{ $slide->subtitle }}</td>
                <td class="image"><img src="{{ asset('storage/'.$slide->image) }}" style="max-width:100px;"></td>
                <td class="actions">
                    <button onclick="editRowSlide({{ $slide->id }})">Edit</button>
                    <form action="{{ route('slides.destroy', $slide->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" onclick="return confirm('Yakin hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</section>

{{-- SECTION--}}
<section class="admin-card">
    <h2>Kelola Section</h2>

    <form action="{{ route('sections.store') }}" method="POST" enctype="multipart/form-data" class="add-form">
        @csrf
        <div class="form-row">
            <input type="text" name="title" placeholder="Judul" required>
            <input type="text" name="subtitle" placeholder="Subjudul">
            <input type="file" name="image">
            <textarea name="description" placeholder="Deskripsi" required></textarea>
            <button type="submit" class="btn-primary">Tambah Section</button>
        </div>
    </form>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Judul</th>
                <th>Subjudul</th>
                <th>Deskripsi</th>
                <th>Gambar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sections as $section)
            <tr id="section-{{ $section->id }}">
                <td class="editable title">{{ $section->title }}</td>
                <td class="editable subtitle">{{ $section->subtitle }}</td>
                <td class="editable description">{{ $section->description }}</td>
                <td class="editable image">
                    @if($section->image)
                        <img src="{{ asset('storage/'.$section->image) }}" class="preview-img" id="preview-{{ $section->id }}">
                    @else
                        <small>Tidak ada gambar</small>
                    @endif
                </td>
                <td class="actions">
                    <button class="btn-edit" onclick="editRowSection({{ $section->id }})">Edit</button>
                    <form action="{{ route('sections.destroy', $section->id) }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-delete">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</section>

{{--JAVASCRIPT --}}
<script>
    // Fungsi edit Section
    function editRowSection(id) {
        const row = document.getElementById(`section-${id}`);
        const btn = row.querySelector('.btn-edit');
        const title = row.querySelector('.title');
        const subtitle = row.querySelector('.subtitle');
        const description = row.querySelector('.description');
        const image = row.querySelector('.image');

        if (btn.innerText === 'Simpan') {
            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('title', title.querySelector('input').value);
            formData.append('subtitle', subtitle.querySelector('input').value);
            formData.append('description', description.querySelector('textarea').value);
            const file = image.querySelector('input[type=file]')?.files[0];
            if (file) formData.append('image', file);

            fetch(`/admin/sections/${id}`, { method: 'POST', body: formData })
                .then(res => res.ok ? location.reload() : alert('Gagal update section!'));
            return;
        }

        const oldTitle = title.textContent.trim();
        const oldSubtitle = subtitle.textContent.trim();
        const oldDesc = description.textContent.trim();
        const oldImg = image.querySelector('img') ? image.querySelector('img').src : '';

        title.innerHTML = `<input type="text" value="${oldTitle}" class="form-input">`;
        subtitle.innerHTML = `<input type="text" value="${oldSubtitle}" class="form-input">`;
        description.innerHTML = `<textarea class="form-input">${oldDesc}</textarea>`;
        image.innerHTML = `<img src="${oldImg}" class="preview-img"><input type="file" class="form-input mt-1">`;

        btn.innerText = 'Simpan';
        btn.classList.add('saving');
    }

    // Fungsi edit Slide (sama seperti sebelumnya)
    function editRowSlide(id) {
        const row = document.getElementById(`slide-${id}`);
        const actionsTd = row.querySelector('.actions');
        if(row.classList.contains('editing')) return;
        row.classList.add('editing');

        const dateTd = row.querySelector('.date');
        const titleTd = row.querySelector('.title');
        const subtitleTd = row.querySelector('.subtitle');
        const imageTd = row.querySelector('.image');

        const oldDate = dateTd.textContent;
        const oldTitle = titleTd.textContent;
        const oldSubtitle = subtitleTd.textContent;
        const oldImg = imageTd.querySelector('img').src;

        dateTd.innerHTML = `<input type="date" value="${oldDate}">`;
        titleTd.innerHTML = `<input type="text" value="${oldTitle}">`;
        subtitleTd.innerHTML = `<input type="text" value="${oldSubtitle}">`;
        imageTd.innerHTML = `<img src="${oldImg}" style="max-width:100px;"><br><input type="file">`;

        actionsTd.innerHTML = `
            <button class="save-btn">Simpan</button>
            <button class="cancel-btn">Batal</button>
        `;

        actionsTd.querySelector('.cancel-btn').addEventListener('click', () => {
            dateTd.textContent = oldDate;
            titleTd.textContent = oldTitle;
            subtitleTd.textContent = oldSubtitle;
            imageTd.innerHTML = `<img src="${oldImg}" style="max-width:100px;">`;
            actionsTd.innerHTML = `
                <button onclick="editRowSlide(${id})">Edit</button>
                <form action="/admin/slides/${id}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Yakin hapus?')">Hapus</button>
                </form>
            `;
            row.classList.remove('editing');
        });

        actionsTd.querySelector('.save-btn').addEventListener('click', () => {
            const formData = new FormData();
            formData.append('_method','PUT');
            formData.append('_token','{{ csrf_token() }}');
            formData.append('date', dateTd.querySelector('input').value);
            formData.append('title', titleTd.querySelector('input').value);
            formData.append('subtitle', subtitleTd.querySelector('input').value);

            const fileInput = imageTd.querySelector('input[type="file"]');
            if(fileInput && fileInput.files[0]) formData.append('image', fileInput.files[0]);

            fetch(`/admin/slides/${id}`, { method: 'POST', body: formData })
                .then(res => res.ok ? location.reload() : alert('Gagal update slide!'));
        });
    }
</script>

@endsection
