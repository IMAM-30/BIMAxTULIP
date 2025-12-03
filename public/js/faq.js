document.addEventListener('DOMContentLoaded', function() {

    const faqItems      = Array.from(document.querySelectorAll('.faq-item'));
    const faqQuestions  = document.querySelectorAll('.faq-question');
    const filterBtns    = document.querySelectorAll('.filter-btn');
    const searchInput   = document.getElementById('faqSearch');

    const loadMoreBtn   = document.getElementById('faqLoadMore');
    const showLessBtn   = document.getElementById('faqShowLess');

    const ITEMS_PER_PAGE = 5;
    let visibleCount     = ITEMS_PER_PAGE;
    let activeCategory   = 'all';
    let searchQuery      = '';

    function refreshFaqItems() {
        let shownIndex   = 0;
        let totalMatches = 0;

        faqItems.forEach(item => {
            const itemCategory = item.dataset.category;
            const questionText = item
                .querySelector('.faq-question-text')
                .textContent
                .toLowerCase();

            const matchCategory = (activeCategory === 'all' || itemCategory === activeCategory);
            const matchSearch   = questionText.includes(searchQuery);

            const matches = matchCategory && matchSearch;

            if (matches) {
                totalMatches++;
                shownIndex++;

                if (shownIndex <= visibleCount) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                    resetItem(item);
                }
            } else {
                item.style.display = 'none';
                resetItem(item);
            }
        });

        // TOMBOL
        if (totalMatches === 0) {
            loadMoreBtn.style.display = 'none';
            showLessBtn.style.display = 'none';
            return;
        }

        if (visibleCount >= totalMatches) {
            loadMoreBtn.style.display = 'none';
            showLessBtn.style.display = totalMatches > ITEMS_PER_PAGE ? 'inline-block' : 'none';
        } else {
            loadMoreBtn.style.display = 'inline-block';
            showLessBtn.style.display = totalMatches > ITEMS_PER_PAGE ? 'inline-block' : 'none';
        }
    }

    // Reset accordion jika disembunyikan
    function resetItem(item) {
        item.classList.remove('active');
        const ans = item.querySelector('.faq-answer');
        const ic  = item.querySelector('.faq-icon');

        if (ans) ans.style.maxHeight = null;
        if (ic)  ic.style.transform = 'rotate(0deg)';
    }

    faqQuestions.forEach(q => {
        q.addEventListener('click', function() {
            const parent = this.closest('.faq-item');
            const answer = parent.querySelector('.faq-answer');
            const icon   = this.querySelector('.faq-icon');

            const isActive = parent.classList.contains('active');

            faqItems.forEach(item => resetItem(item));

            if (!isActive) {
                parent.classList.add('active');
                answer.style.maxHeight = answer.scrollHeight + 'px';
                icon.style.transform = 'rotate(90deg)';
            }
        });
    });

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            activeCategory = this.dataset.category;
            visibleCount = ITEMS_PER_PAGE;

            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            refreshFaqItems();
        });
    });


    searchInput.addEventListener('input', function() {
        searchQuery = this.value.toLowerCase().trim();
        visibleCount = ITEMS_PER_PAGE;
        refreshFaqItems();
    });

    loadMoreBtn.addEventListener('click', function() {
        visibleCount += ITEMS_PER_PAGE;
        refreshFaqItems();
    });

    showLessBtn.addEventListener('click', function() {
        visibleCount = ITEMS_PER_PAGE;
        refreshFaqItems();
    });

    refreshFaqItems();
});
