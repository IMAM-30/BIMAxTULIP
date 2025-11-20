document.addEventListener("DOMContentLoaded", () => {
    const slides = document.querySelectorAll(".slide-item");
    const buttons = document.querySelectorAll(".nav-btn");
    const container = document.querySelector(".slider-container");
    let currentIndex = 0;

    function showSlide(index) {
        const offset = -index * 100;
        container.style.transform = `translateX(${offset}%)`;

        buttons.forEach(btn => btn.classList.remove("active"));
        if (buttons[index]) buttons[index].classList.add("active");
    }

    buttons.forEach((btn, i) => {
        btn.addEventListener("click", () => {
            currentIndex = i;
            showSlide(i);
        });
    });

    showSlide(currentIndex);
});
