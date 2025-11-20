// news.js - handle "More" toggle and image modal enlarge
document.addEventListener('DOMContentLoaded', function () {
  // More button toggle
  document.querySelectorAll('.news-item').forEach(function(item){
    const btn = item.querySelector('.btn-more');
    const full = item.querySelector('.news-full');

    if(btn && full){
      btn.addEventListener('click', function(){
        const expanded = item.classList.toggle('expanded');
        btn.setAttribute('aria-expanded', expanded ? 'true' : 'false');
        btn.textContent = expanded ? 'Less' : 'More';

        // optional: animate max-height using scrollHeight
        if(expanded){
          full.style.maxHeight = full.scrollHeight + 'px';
        } else {
          full.style.maxHeight = null;
        }
      });
    }
  });

  // Image modal
  // create modal element once
  let modalOverlay = document.createElement('div');
  modalOverlay.className = 'news-modal-overlay';
  modalOverlay.innerHTML = '<div class="news-modal"><img src="" alt="Preview"></div>';
  document.body.appendChild(modalOverlay);

  const modalImg = modalOverlay.querySelector('img');

  document.querySelectorAll('.news-image').forEach(function(img){
    img.addEventListener('click', function(){
      const src = img.getAttribute('src');
      modalImg.setAttribute('src', src);
      modalOverlay.classList.add('active');
      // prevent body scroll
      document.body.style.overflow = 'hidden';
    });
  });

  // close modal on overlay click or Escape
  modalOverlay.addEventListener('click', function(e){
    if(e.target === modalOverlay || e.target === modalImg){
      modalOverlay.classList.remove('active');
      modalImg.setAttribute('src','');
      document.body.style.overflow = '';
    }
  });

  document.addEventListener('keydown', function(e){
    if(e.key === 'Escape' && modalOverlay.classList.contains('active')){
      modalOverlay.classList.remove('active');
      modalImg.setAttribute('src','');
      document.body.style.overflow = '';
    }
  });
});
