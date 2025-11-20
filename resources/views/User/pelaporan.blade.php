@extends('Components.layout')

@section('title', 'Pelaporan')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/user-css/home.css') }}">
<link rel="stylesheet" href="{{ asset('css/user-css/map-popup.css') }}">
<link rel="stylesheet" href="{{ asset('css/user-css/pelaporan.css') }}">

@endpush

@section('content')
@include('components.hero', ['slides' => $slides])

<div class="container-pelaporan">
    <div class="form-wrapper">
        <h2>Formulir Laporan Banjir</h2>
        <p>Silakan isi formulir di bawah ini untuk melaporkan kejadian banjir di sekitar Anda.</p>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form id="laporanForm" action="/pelaporan" method="POST">
            @csrf

            <div class="form-group">
                <label for="nama">Nama Pelapor</label>
                <input type="text" id="nama" name="nama" maxlength="70" placeholder="Opsional (Kosongkan untuk 'Anonymouse')">
                <small>Hanya huruf & angka.</small>
            </div>

            <div class="form-group">
                <label for="no_telepon">No. Telepon (WhatsApp)</label>
                <input type="tel" id="no_telepon" name="no_telepon" maxlength="25" required placeholder="Contoh: 081234567890">
                <small>Wajib diisi. Hanya angka. (Nanti akan digunakan untuk OTP)</small>
            </div>

            <div class="form-group">
                <label for="kecamatan">Kecamatan</label>
                <select id="kecamatan" name="kecamatan" required>
                    <option value="" disabled selected>-- Pilih Kecamatan --</option>
                    <option value="Bacukiki">Bacukiki</option>
                    <option value="Bacukiki Barat">Bacukiki Barat</option>
                    <option value="Ujung">Ujung</option>
                    <option value="Soreang">Soreang</option>
                </select>
            </div>

            <div class="form-group">
                <label for="alamat">Alamat Lengkap / Detail Lokasi</label>
                <textarea id="alamat" name="alamat" rows="3" maxlength="100" required placeholder="Contoh: Jl. Merdeka No. 10">{{ old('alamat') }}</textarea>
            </div>

            <div class="form-group">
                <label for="link_postingan">Link Postingan Kejadian</label>
                <input type="url" id="link_postingan" name="link_postingan" maxlength="150" required placeholder="https://...">
            </div>

            <div class="form-group">
                <label for="ketinggian_air">Perkiraan Ketinggian Air</label>
                <input type="text" id="ketinggian_air" name="ketinggian_air" maxlength="30" placeholder="Contoh: 30 cm">
            </div>

            <button id="btnKirim" type="submit" class="btn-submit">Kirim Laporan</button>
        </form>
    </div>
</div>

<!-- Enhanced Modal OTP -->
<div id="otpModal" class="otp-modal" style="display:none;">
  <div class="otp-modal-content" id="otpModalContent" role="dialog" aria-modal="true" aria-labelledby="otpTitle">
    <button class="otp-close" id="otpClose" aria-label="Tutup">×</button>
    <h3 id="otpTitle" class="otp-title">Masukkan Kode OTP</h3>
    <p class="otp-sub">Kode dikirim ke nomor <strong id="otpToNumber"></strong></p>

    <div style="display:flex; flex-direction:column; align-items:center;">
      <div class="otp-inputs" id="otpInputs" aria-label="Kode OTP">
        <input inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-digit" aria-label="Digit 1" />
        <input inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-digit" aria-label="Digit 2" />
        <input inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-digit" aria-label="Digit 3" />
        <input inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-digit" aria-label="Digit 4" />
        <input inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-digit" aria-label="Digit 5" />
        <input inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-digit" aria-label="Digit 6" />
      </div>

      <div class="otp-actions" style="width:100%; padding:0 10px;">
        <div class="small-muted">Sisa waktu: <span class="otp-timer" id="otpTimer">05:00</span></div>
        <div style="display:flex; gap:8px;">
          <button id="resendOtpBtn" class="btn-resend" type="button">Kirim Ulang</button>
          <button id="verifyOtpBtn" class="btn-verify">Verifikasi</button>
        </div>
      </div>

      <div id="otpFeedback" class="otp-feedback"></div>
      <div style="margin-top:6px;"><button id="otpAutofillBtn" style="display:none;font-size:12px;border:0;background:#e6f0ff;padding:6px 8px;border-radius:6px;cursor:pointer;">Auto-fill OTP (dev)</button></div>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  // form references
  const form = document.getElementById('laporanForm');
  const btnKirim = document.getElementById('btnKirim');

  // otp modal refs
  const otpModal = document.getElementById('otpModal');
  const otpModalContent = document.getElementById('otpModalContent');
  const otpClose = document.getElementById('otpClose');
  const otpToNumber = document.getElementById('otpToNumber');
  const otpInputs = Array.from(document.querySelectorAll('.otp-digit'));
  const verifyOtpBtn = document.getElementById('verifyOtpBtn');
  const resendOtpBtn = document.getElementById('resendOtpBtn');
  const otpFeedback = document.getElementById('otpFeedback');
  const otpTimerEl = document.getElementById('otpTimer');
  const otpAutofillBtn = document.getElementById('otpAutofillBtn');

  let timerInterval = null;
  let secondsLeft = 0;
  let currentSessionId = null;
  let lastSentPhone = null;
  let debugOtpSaved = null;

  // helper to open modal
  function openModalFor(phone, sessionId, debugOtp=null) {
    otpToNumber.textContent = phone;
    currentSessionId = sessionId;
    lastSentPhone = phone;
    debugOtpSaved = debugOtp;
    otpFeedback.textContent = '';
    clearInputs();
    otpModal.style.display = 'flex';
    otpInputs[0].focus();
    startTimer(5 * 60);
    if (debugOtp) {
      otpAutofillBtn.style.display = 'inline-block';
      otpAutofillBtn.dataset.debug = debugOtp;
    } else {
      otpAutofillBtn.style.display = 'none';
    }
  }

  function closeModal() { otpModal.style.display = 'none'; stopTimer(); currentSessionId = null; debugOtpSaved = null; }
  otpClose.addEventListener('click', closeModal);

  function clearInputs() { otpInputs.forEach(i => { i.value = ''; i.classList.remove('invalid'); }); }
  function getOtpValue() { return otpInputs.map(i => i.value).join(''); }

  // input handling (auto-advance, backspace, paste) + auto-submit when full
  otpInputs.forEach((input, idx) => {
    input.addEventListener('input', (e) => {
      const val = e.target.value.replace(/\D/g,'').slice(0,1);
      e.target.value = val;
      e.target.classList.remove('invalid');
      if (val && idx < otpInputs.length - 1) otpInputs[idx + 1].focus();
      // if all filled -> auto-submit
      if (getOtpValue().length === otpInputs.length) {
        // small delay to allow last digit UI update
        setTimeout(() => { attemptVerify(); }, 150);
      }
    });
    input.addEventListener('keydown', (e) => {
      if (e.key === 'Backspace' && !e.target.value && idx > 0) otpInputs[idx - 1].focus();
      if (e.key === 'ArrowLeft' && idx > 0) otpInputs[idx - 1].focus();
      if (e.key === 'ArrowRight' && idx < otpInputs.length - 1) otpInputs[idx + 1].focus();
    });
    input.addEventListener('paste', (e) => {
      e.preventDefault();
      const paste = (e.clipboardData || window.clipboardData).getData('text').trim();
      const digits = paste.replace(/\D/g,'').slice(0, otpInputs.length).split('');
      if (digits.length) {
        digits.forEach((d, i) => { otpInputs[i].value = d; });
        const lastIndex = Math.min(digits.length - 1, otpInputs.length - 1);
        otpInputs[Math.min(lastIndex + 1, otpInputs.length - 1)].focus();
        if (getOtpValue().length === otpInputs.length) setTimeout(() => { attemptVerify(); }, 150);
      }
    });
  });

  // timer functions
  function startTimer(seconds) {
    secondsLeft = seconds; updateTimerDisplay(); resendOtpBtn.disabled = true;
    if (timerInterval) clearInterval(timerInterval);
    timerInterval = setInterval(() => { secondsLeft--; updateTimerDisplay(); if (secondsLeft <= 0) { stopTimer(); resendOtpBtn.disabled = false; } }, 1000);
  }
  function stopTimer() { if (timerInterval) clearInterval(timerInterval); timerInterval = null; otpTimerEl.textContent = '00:00'; resendOtpBtn.disabled = false; }
  function updateTimerDisplay() { const mm = String(Math.floor(secondsLeft/60)).padStart(2,'0'); const ss = String(secondsLeft % 60).padStart(2,'0'); otpTimerEl.textContent = `${mm}:${ss}`; }

  // autofill dev
  otpAutofillBtn.addEventListener('click', () => {
    const debug = otpAutofillBtn.dataset.debug; if (!debug) return; const digits = debug.toString().split(''); digits.forEach((d,i) => { if (otpInputs[i]) otpInputs[i].value = d; });
    setTimeout(() => attemptVerify(), 120);
  });

  // visual shake on incorrect
  function shakeModal() {
    otpModalContent.classList.remove('shake');
    // force reflow
    void otpModalContent.offsetWidth;
    otpModalContent.classList.add('shake');
    setTimeout(() => { otpModalContent.classList.remove('shake'); }, 420);
  }

  // attempt verify (shared by auto & click)
  async function attemptVerify() {
    otpFeedback.textContent = '';
    const otp = getOtpValue();
    if (otp.length !== otpInputs.length) {
      otpFeedback.textContent = 'Masukkan 6 digit kode OTP.'; return;
    }

    verifyOtpBtn.disabled = true; verifyOtpBtn.textContent = 'Memverifikasi...';

    const payload = new FormData(form);
    payload.append('session_id', currentSessionId);
    payload.append('otp', otp);

    try {
      const res = await fetch('/pelaporan/verify-otp', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
        body: payload
      });
      const json = await res.json();

      if (json.status === 'ok') {
        closeModal(); alert(json.message || 'Laporan terkirim.'); window.location.reload();
      } else {
        // show validation errors or message
        if (json.errors) { const msgs = []; Object.values(json.errors).forEach(arr => msgs.push(arr.join(' '))); otpFeedback.textContent = msgs.join(' '); }
        else { otpFeedback.textContent = json.message || 'OTP salah atau kadaluarsa.'; }
        // mark inputs invalid & shake
        otpInputs.forEach(i => i.classList.add('invalid'));
        shakeModal();
      }
    } catch (err) {
      otpFeedback.textContent = 'Gagal verifikasi OTP.'; console.error(err); otpInputs.forEach(i => i.classList.add('invalid')); shakeModal();
    } finally { verifyOtpBtn.disabled = false; verifyOtpBtn.textContent = 'Verifikasi'; }
  }

  // Verify OTP on click
  verifyOtpBtn.addEventListener('click', attemptVerify);

  // Resend OTP
  resendOtpBtn.addEventListener('click', async () => {
    otpFeedback.textContent = '';
    if (!lastSentPhone) { otpFeedback.textContent = 'Nomor telepon tidak tersedia untuk mengirim ulang.'; return; }
    resendOtpBtn.disabled = true; resendOtpBtn.textContent = 'Mengirim ulang...';

    try {
      const f = new FormData(); f.append('no_telepon', lastSentPhone);
      const res = await fetch('/pelaporan/request-otp', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content') }, body: f });
      const json = await res.json();
      if (json.status === 'ok') {
        currentSessionId = json.session_id;
        if (json.debug_otp) { otpAutofillBtn.style.display = 'inline-block'; otpAutofillBtn.dataset.debug = json.debug_otp; } else { otpAutofillBtn.style.display = 'none'; }
        otpFeedback.textContent = 'OTP terkirim ulang.'; startTimer(json.expires_in || 5*60);
      } else { otpFeedback.textContent = json.message || 'Gagal mengirim ulang OTP.'; }
    } catch (err) { otpFeedback.textContent = 'Gagal mengirim ulang OTP.'; console.error(err); }
    finally { resendOtpBtn.disabled = false; resendOtpBtn.textContent = 'Kirim Ulang'; }
  });

  // Intercept form submit -> request OTP then open modal
  form.addEventListener('submit', async (e) => {
    e.preventDefault(); btnKirim.disabled = true; btnKirim.textContent = 'Mengirim...';
    const formData = new FormData(form);
    try {
      const res = await fetch('/pelaporan/request-otp', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content') }, body: formData });
      const json = await res.json();
      if (json.status === 'ok') {
        // open enhanced modal and pass debug_otp if present
        lastSentPhone = form.no_telepon.value.trim();
        window.showModalFor ? window.showModalFor(lastSentPhone, json.session_id, json.debug_otp || null) : openModalFor(lastSentPhone, json.session_id, json.debug_otp || null);
      } else {
        if (json.errors) { const msgs = []; Object.values(json.errors).forEach(arr => msgs.push(arr.join('\n'))); alert(msgs.join('\n')); }
        else { alert(json.message || 'Gagal mengirim OTP.'); }
      }
    } catch (err) { alert('Terjadi kesalahan saat mengirim OTP.'); console.error(err); }
    finally { btnKirim.disabled = false; btnKirim.textContent = 'Kirim Laporan'; }
  });

  // expose for compatibility
  window.showModalFor = function(phone, sessionId, debugOtp=null) { openModalFor(phone, sessionId, debugOtp); };
});
</script>
@endpush
@endsection
