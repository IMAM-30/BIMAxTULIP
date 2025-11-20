<!doctype html>
<html>
<head>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta charset="utf-8">
  <title>Test Zenziva</title>
</head>
<body>
  <h3>Test Zenziva SMS</h3>
  <form id="f" action="/test-zenziva/send" method="POST">
    @csrf
    <input name="phone" placeholder="0812..." />
    <input name="message" placeholder="Pesan (opsional)" />
    <button type="submit">Kirim</button>
  </form>
  <pre id="result"></pre>

  <script>
    document.getElementById('f').addEventListener('submit', async function(e) {
      e.preventDefault();
      const fd = new FormData(this);
      const res = await fetch(this.action, {
        method:'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
        body: fd
      });
      const j = await res.json();
      document.getElementById('result').textContent = JSON.stringify(j, null, 2);
    });
  </script>
</body>
</html>
