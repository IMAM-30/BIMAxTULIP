document.addEventListener('DOMContentLoaded', function () {
    var el = document.getElementById('whatsapp-bubble');
    if (!el) return;

    var phone = el.getAttribute('data-phone') || '';
    var message = el.getAttribute('data-message') || '';
    // pastikan nomor disimpan seperti +628123... tanpa spasi
    phone = phone.replace(/\s+/g, '');

    var btn = document.getElementById('whatsapp-open');

    btn.addEventListener('click', function (e) {
        e.preventDefault();

        if (!phone) {
            console.warn('WhatsApp phone number not configured.');
            return;
        }

        // convert +62 or leading 0 handling — kita anggap admin menyimpan +62
        // WhatsApp URL:
        var base = 'https://wa.me/';
        var rawNumber = phone.replace(/^\+/, ''); // hapus plus jika ada
        // If custom message is present (already encoded by blade rawurlencode)
        var url = base + rawNumber;
        if (message && message.length > 0) {
            url += '?text=' + message;
        }

        // buka di tab baru
        window.open(url, '_blank');
    });
});
