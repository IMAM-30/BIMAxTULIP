document.addEventListener("DOMContentLoaded", () => {
    const slider = document.getElementById("heroSlider");
    const slides = document.querySelectorAll(".hero-slide");
    const prevBtn = document.querySelector(".hero-prev");
    const nextBtn = document.querySelector(".hero-next");
    let currentIndex = 0;

    function showSlide(index) {
        if (index < 0) index = slides.length - 1;
        if (index >= slides.length) index = 0;
        currentIndex = index;
        const offset = -index * 100;
        slider.style.transform = `translateX(${offset}%)`;
    }

    prevBtn.addEventListener("click", () => {
        showSlide(currentIndex - 1);
    });

    nextBtn.addEventListener("click", () => {
        showSlide(currentIndex + 1);
    });

    // Auto-slide
    setInterval(() => {
        showSlide(currentIndex + 1);
    }, 5000);

    showSlide(currentIndex);
});
