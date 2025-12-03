document.addEventListener('DOMContentLoaded', function () {
    const elements = document.querySelectorAll('.reveal');

    if (!elements.length) return;

    if (!('IntersectionObserver' in window)) {
        elements.forEach(el => el.classList.add('show'));
        return;
    }

    elements.forEach(el => el.classList.add('reveal-init'));

    const options = {
        root: null,
        rootMargin: '0px 0px -10% 0px',
        threshold: 0.15
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('show');
            } else {
                entry.target.classList.remove('show'); // hide again
            }
        });
    }, options);

    elements.forEach(el => observer.observe(el));
});
