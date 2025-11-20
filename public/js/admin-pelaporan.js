document.addEventListener('DOMContentLoaded', function() {
    const deleteForms = document.querySelectorAll('.form-hapus');

    deleteForms.forEach(form => {
        form.addEventListener('submit', function(event) {
            const confirmation = confirm('Apakah Anda yakin ingin menghapus laporan ini? Data tidak dapat dikembalikan.');
            if (!confirmation) {
                event.preventDefault();
            }
        });
    });
});
