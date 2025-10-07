let currentIndex = 0;

function goToSlide(index) {
    const slides = document.getElementById('slides');
    const slideWidth = slides.children[0].clientWidth;
    slides.style.transform = `translateX(-${index * slideWidth}px)`;
    currentIndex = index;
}

// Responsive handling (agar tidak error saat resize)
window.addEventListener('resize', () => goToSlide(currentIndex));
