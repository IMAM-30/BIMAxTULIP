{{-- ================= SECTION SECTION ================= --}}
<section class="admin-card">
    <h2>Kelola Section</h2>

    {{-- Pesan sukses --}}
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Form tambah section --}}
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

    {{-- Tabel section --}}
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
                    <button class="btn-edit" onclick="editRow({{ $section->id }})">Edit</button>
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

<script>
// Fungsi edit Section
function editRow(id) {
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

    // Ambil data lama
    const oldTitle = title.textContent.trim();
    const oldSubtitle = subtitle.textContent.trim();
    const oldDesc = description.textContent.trim();
    const oldImg = image.querySelector('img') ? image.querySelector('img').src : '';

    // Ubah ke input
    title.innerHTML = `<input type="text" value="${oldTitle}" class="form-input">`;
    subtitle.innerHTML = `<input type="text" value="${oldSubtitle}" class="form-input">`;
    description.innerHTML = `<textarea class="form-input">${oldDesc}</textarea>`;
    image.innerHTML = `<img src="${oldImg}" class="preview-img"><input type="file" class="form-input mt-1">`;

    btn.innerText = 'Simpan';
    btn.classList.add('saving');
}
</script>