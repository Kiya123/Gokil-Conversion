<div class="card error-card" id="errorCard" style="display:none;">
  <div class="result-header">
    <div class="result-icon error">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
      </svg>
    </div>
    <div>
      <h2 class="result-title">Konversi Gagal</h2>
      <p class="result-sub error-text" id="errorMsg">—</p>
    </div>
  </div>
  <div class="error-checklist">
    <p class="checklist-title">Checklist:</p>
    <ul>
      <li>Pastikan server Dier nyala dan Apache running di XAMPP</li>
      <li>Cek IP server di <code>config.php</code> sudah benar</li>
      <li>Coba ping IP server dari CMD dulu</li>
      <li>Pastikan port 80 tidak diblokir firewall Windows</li>
    </ul>
  </div>
</div>
