// =====================
// SLIDER INFO SECTION
// =====================
let currentSlide = 0;

function goToSlide(index) {
    const slides = document.getElementById('slides');
    const totalSlides = slides.children.length;
    const buttons = document.querySelectorAll('.nav-buttons button');

    if (index < 0) index = totalSlides - 1;
    if (index >= totalSlides) index = 0;

    currentSlide = index;
    slides.style.transform = `translateX(-${index * 100}%)`;

    // update tombol aktif
    buttons.forEach((btn, i) => {
        btn.classList.toggle('active', i === index);
    });
}

// auto-slide tiap 5 detik (bisa dihapus kalau tidak mau otomatis)
setInterval(() => {
    goToSlide(currentSlide + 1);
}, 5000);


// =====================
// POPUP DETAIL MAP (Responsif, bisa scroll)
// =====================
document.addEventListener("DOMContentLoaded", () => {
    const overlay = document.getElementById("mapPopupOverlay");
    const popupImage = document.getElementById("popupMapImage");
    const popupNama = document.getElementById("popupMapNama");
    const popupAlamat = document.getElementById("popupMapAlamat");
    const popupTanggal = document.getElementById("popupMapTanggal");
    const closeBtn = document.querySelector(".popup-close");

    // Klik gambar di popup leaflet → buka overlay card
    document.addEventListener("click", (e) => {
        if (e.target.classList.contains("popup-img")) {
            const imgSrc = e.target.getAttribute("src");
            const nama = e.target.getAttribute("alt") || "Tanpa Nama";
            const card = e.target.closest(".popup-card");
            const alamat = card?.querySelector(".popup-location")?.textContent || "-";
            const tanggal = card?.querySelector(".popup-date")?.textContent || "-";

            popupImage.src = imgSrc;
            popupNama.textContent = nama;
            popupAlamat.textContent = alamat;
            popupTanggal.textContent = tanggal;

            overlay.classList.add("active");
            document.body.style.overflow = "hidden"; // nonaktifkan scroll body
        }
    });

    // Tutup popup via tombol X
    closeBtn.addEventListener("click", closeMapPopup);

    // Tutup popup jika klik di luar kartu
    overlay.addEventListener("click", (e) => {
        if (e.target.id === "mapPopupOverlay") closeMapPopup();
    });
});

function closeMapPopup() {
    const overlay = document.getElementById("mapPopupOverlay");
    overlay.classList.remove("active");
    document.body.style.overflow = ""; // aktifkan kembali scroll body
}
