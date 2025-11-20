// public/js/news-loadmore.js
document.addEventListener('DOMContentLoaded', function () {
  const batch = 3; // jumlah item per klik
  const list = document.getElementById('newsList');
  const btn = document.getElementById('btnLoadMore');

  if (!list || !btn) return;

  function countHidden() {
    return Array.from(list.querySelectorAll('.news-item')).filter(it => it.style.display === 'none').length;
  }

  function revealNext() {
    const hiddenItems = Array.from(list.querySelectorAll('.news-item')).filter(it => it.style.display === 'none');
    for (let i = 0; i < Math.min(batch, hiddenItems.length); i++) {
      hiddenItems[i].style.display = ''; 
    }
    if (countHidden() === 0) {
      btn.style.display = 'none';
    } else {
      const firstRevealed = list.querySelector('.news-item:not([style*="display:none"])');
      if (firstRevealed) {
        firstRevealed.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    }
  }

  btn.addEventListener('click', function (e) {
    e.preventDefault();
    revealNext();
  });

  // jika jumlah awal <= batch, sembunyikan tombol
  const totalItems = list.querySelectorAll('.news-item').length;
  if (totalItems <= batch) {
    btn.style.display = 'none';
  }
});
