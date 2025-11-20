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

