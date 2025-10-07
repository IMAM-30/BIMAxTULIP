document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("mapForm");
    const btnSubmit = document.getElementById("btnSubmit");
    const btnCancel = document.getElementById("btnCancel");

    // ======================
    // INISIALISASI LEAFLET
    // ======================
    const map = L.map('map').setView([-6.200000, 106.816666], 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    let newMarker = null;

    // Tambahkan marker dari tabel
    document.querySelectorAll("#mapTableBody tr[data-id]").forEach(row => {
        const coords = row.children[8].textContent.split(',');
        const lat = parseFloat(coords[0].trim());
        const lon = parseFloat(coords[1].trim());
        const nama = row.children[1].textContent.trim();
        const tanggal = row.children[2].textContent.trim();
        const alamat = row.children[3].textContent.trim();
        const ketinggian = row.children[4].textContent.trim();
        const popup = `<b>${nama}</b><br>${tanggal}<br>${alamat}<br>${ketinggian}`;
        L.marker([lat, lon]).addTo(map).bindPopup(popup);
    });

    setTimeout(() => map.invalidateSize(), 100);

    // ======================
    // KLIK PETA
    // ======================
    map.on('click', function(e) {
        const { lat, lng } = e.latlng;
        document.getElementById("latitude").value = lat.toFixed(6);
        document.getElementById("longitude").value = lng.toFixed(6);

        if (newMarker) map.removeLayer(newMarker);
        newMarker = L.marker([lat, lng], { draggable: true }).addTo(map);

        newMarker.on('dragend', function(event) {
            const pos = event.target.getLatLng();
            document.getElementById("latitude").value = pos.lat.toFixed(6);
            document.getElementById("longitude").value = pos.lng.toFixed(6);
        });
    });

    // ======================
    // SUBMIT FORM (Tambah / Edit)
    // ======================
    form.addEventListener("submit", function (e) {
        e.preventDefault();
        const id = document.getElementById("id").value;
        const formData = new FormData(form);
        let url = id ? `/admin/maps/${id}` : `/admin/maps`;
        if (id) formData.append("_method", "PUT");

        fetch(url, {
            method: "POST",
            body: formData,
            headers: {
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
            },
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) location.reload();
            else alert("Gagal menyimpan data!");
        })
        .catch(err => console.error(err));
    });

    // ======================
    // EDIT
    // ======================
    document.querySelectorAll(".btn-edit").forEach(btn => {
        btn.addEventListener("click", () => {
            const row = btn.closest("tr");
            const latlon = row.children[8].textContent.split(',');
            const lat = parseFloat(latlon[0].trim());
            const lon = parseFloat(latlon[1].trim());

            document.getElementById("id").value = btn.dataset.id;
            document.getElementById("nama_lokasi").value = row.children[1].textContent.trim();
            document.getElementById("tanggal").value = row.children[2].textContent.trim();
            document.getElementById("alamat").value = row.children[3].textContent.trim();
            document.getElementById("ketinggian_air").value = parseInt(row.children[4].textContent);
            document.getElementById("rumah_terdampak").value = parseInt(row.children[5].textContent);
            document.getElementById("jumlah_korban").value = parseInt(row.children[6].textContent);
            document.getElementById("luas_cakupan").value = parseInt(row.children[7].textContent);
            document.getElementById("latitude").value = lat.toFixed(6);
            document.getElementById("longitude").value = lon.toFixed(6);

            btnSubmit.textContent = "Simpan Perubahan";
            btnCancel.style.display = "inline-block";

            if (newMarker) map.removeLayer(newMarker);
            newMarker = L.marker([lat, lon], { draggable: true }).addTo(map);
            map.setView([lat, lon], 15);

            newMarker.on('dragend', function(event) {
                const pos = event.target.getLatLng();
                document.getElementById("latitude").value = pos.lat.toFixed(6);
                document.getElementById("longitude").value = pos.lng.toFixed(6);
            });
        });
    });

    // ======================
    // CANCEL
    // ======================
    btnCancel.addEventListener("click", () => {
        form.reset();
        document.getElementById("id").value = "";
        btnSubmit.textContent = "Tambah Data";
        btnCancel.style.display = "none";
        if (newMarker) map.removeLayer(newMarker);
    });

    // ======================
    // DELETE
    // ======================
    document.querySelectorAll(".btn-delete").forEach(btn => {
        btn.addEventListener("click", () => {
            if (!confirm("Yakin ingin menghapus data ini?")) return;
            fetch(`/admin/maps/${btn.dataset.id}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                },
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) location.reload();
                else alert("Gagal menghapus data!");
            })
            .catch(err => console.error(err));
        });
    });
});
