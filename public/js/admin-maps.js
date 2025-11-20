document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("mapForm");
    const btnAdd = document.getElementById("btnAdd");
    const btnSubmit = document.getElementById("btnSubmit");
    const btnCancel = document.getElementById("btnCancel");
    let newMarker = null;

    const map = L.map('map').setView([-6.2, 106.816666], 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Load marker dari tabel
    document.querySelectorAll("#mapTableBody tr[data-id]").forEach(row => {
        const coords = row.children[8].textContent.split(',');
        const lat = parseFloat(coords[0].trim());
        const lon = parseFloat(coords[1].trim());
        const popup = `<b>${row.children[1].textContent}</b><br>${row.children[2].textContent}<br>${row.children[3].textContent}<br>${row.children[4].textContent}`;
        L.marker([lat, lon]).addTo(map).bindPopup(popup);
    });
    setTimeout(() => map.invalidateSize(), 100);

    // Klik peta untuk tambah marker
    map.on('click', function(e) {
        const { lat, lng } = e.latlng;
        document.getElementById("latitude").value = lat.toFixed(6);
        document.getElementById("longitude").value = lng.toFixed(6);

        if (newMarker) map.removeLayer(newMarker);
        newMarker = L.marker([lat, lng], { draggable: true }).addTo(map);
        newMarker.on('dragend', e => {
            const pos = e.target.getLatLng();
            document.getElementById("latitude").value = pos.lat.toFixed(6);
            document.getElementById("longitude").value = pos.lng.toFixed(6);
        });
    });

    // ======================
    // TAMBAH DATA BARU
    // ======================
    btnAdd.addEventListener("click", function () {
        const formData = new FormData(form);

        fetch('/admin/maps', {
            method: "POST",
            body: formData,
            headers: { "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) location.reload();
            else alert("Gagal menambahkan data!");
        })
        .catch(err => console.error(err));
    });

    // ======================
    // EDIT DATA
    // ======================
    form.addEventListener("submit", function(e) {
        e.preventDefault();
        const id = document.getElementById("id").value;
        if (!id) return;

        const formData = new FormData(form);
        formData.append("_method", "PUT");

        fetch(`/admin/maps/${id}`, {
            method: "POST",
            body: formData,
            headers: { "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) location.reload();
            else alert("Gagal menyimpan perubahan!");
        })
        .catch(err => console.error(err));
    });

    // Tombol Edit dari tabel
    document.querySelectorAll(".btn-edit").forEach(btn => {
        btn.addEventListener("click", () => {
            const row = btn.closest("tr");
            const coords = row.children[8].textContent.split(',');
            const lat = parseFloat(coords[0].trim());
            const lon = parseFloat(coords[1].trim());

            document.getElementById("id").value = btn.dataset.id;
            document.getElementById("nama_lokasi").value = row.children[1].textContent;
            document.getElementById("tanggal").value = row.children[2].textContent;
            document.getElementById("alamat").value = row.children[3].textContent;
            document.getElementById("ketinggian_air").value = parseInt(row.children[4].textContent);
            document.getElementById("rumah_terdampak").value = parseInt(row.children[5].textContent);
            document.getElementById("jumlah_korban").value = parseInt(row.children[6].textContent);
            document.getElementById("luas_cakupan").value = parseInt(row.children[7].textContent);
            document.getElementById("latitude").value = lat.toFixed(6);
            document.getElementById("longitude").value = lon.toFixed(6);

            btnSubmit.style.display = "inline-block";
            btnCancel.style.display = "inline-block";
            btnAdd.style.display = "none";

            if (newMarker) map.removeLayer(newMarker);
            newMarker = L.marker([lat, lon], { draggable: true }).addTo(map);
            map.setView([lat, lon], 15);
            newMarker.on('dragend', e => {
                const pos = e.target.getLatLng();
                document.getElementById("latitude").value = pos.lat.toFixed(6);
                document.getElementById("longitude").value = pos.lng.toFixed(6);
            });
        });
    });

    // Tombol Batal
    btnCancel.addEventListener("click", () => {
        form.reset();
        document.getElementById("id").value = "";
        btnSubmit.style.display = "none";
        btnCancel.style.display = "none";
        btnAdd.style.display = "inline-block";
        if (newMarker) map.removeLayer(newMarker);
    });

    // Tombol Hapus
    document.querySelectorAll(".btn-delete").forEach(btn => {
        btn.addEventListener("click", () => {
            if (!confirm("Yakin ingin menghapus data ini?")) return;
            fetch(`/admin/maps/${btn.dataset.id}`, {
                method: "DELETE",
                headers: { "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value }
            }).then(res => res.json())
            .then(data => { if (data.success) location.reload(); else alert("Gagal menghapus data!"); })
            .catch(err => console.error(err));
        });
    });
});
