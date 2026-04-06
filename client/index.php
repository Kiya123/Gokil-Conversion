<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gokil Conversion — Distributed Image Converter</title>
  <meta name="description" content="Aplikasi konversi gambar terdistribusi berbasis PHP. Kirim gambar dari client ke server via jaringan lokal." />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="assets/style.css" />
</head>
<body>

  <!-- Background blobs -->
  <div class="bg-blob blob-1"></div>
  <div class="bg-blob blob-2"></div>
  <div class="bg-blob blob-3"></div>

  <div class="app-wrapper">

    <!-- Header -->
    <header class="header">
      <div class="logo">
        <div class="logo-icon">GC</div>
        <span class="logo-text">Gokil<span class="logo-accent">Conversion</span></span>
      </div>
      <div class="header-badge">
        <span class="badge-dot"></span>
        Distributed System
      </div>
    </header>

    <!-- Main Content -->
    <main class="main">

      <!-- Hero -->
      <section class="hero">
        <h1 class="hero-title">
          Convert Gambar<br />
          <span class="gradient-text">Lintas Node</span>
        </h1>
        <p class="hero-sub">
          Upload gambar dari browser lu, diproses di server Azkiya via jaringan lokal.
          Supports JPG, PNG, dan WebP.
        </p>
      </section>

      <!-- Converter Card -->
      <div class="card converter-card">

        <!-- Drop Zone -->
        <div class="drop-zone" id="dropZone">
          <div class="drop-zone-inner" id="dropZoneInner">
            <div class="drop-icon">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M3 15v4a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-4"/>
                <polyline points="17 8 12 3 7 8"/>
                <line x1="12" y1="3" x2="12" y2="15"/>
              </svg>
            </div>
            <p class="drop-title">Drag &amp; drop gambar di sini</p>
            <p class="drop-sub">atau</p>
            <label class="btn-browse" for="fileInput">Pilih File</label>
            <input type="file" id="fileInput" accept=".jpg,.jpeg,.png,.webp" hidden />
            <p class="drop-hint">JPG, PNG, WebP — Maks <?= MAX_FILE_SIZE_MB ?> MB</p>
          </div>

          <!-- Preview (hidden by default) -->
          <div class="preview-zone" id="previewZone" style="display:none;">
            <img class="preview-img" id="previewImg" src="" alt="Preview" />
            <div class="preview-info">
              <p class="preview-name" id="previewName">—</p>
              <p class="preview-size" id="previewSize">—</p>
            </div>
            <button class="btn-clear" id="btnClear" title="Hapus file">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
              </svg>
            </button>
          </div>
        </div>

        <!-- Format Selector -->
        <div class="format-section">
          <p class="format-label">Konversi ke:</p>
          <div class="format-options" id="formatOptions">
            <button class="fmt-btn active" data-format="jpg">
              <span class="fmt-badge">JPG</span>
              <span class="fmt-desc">Lossy, kecil</span>
            </button>
            <button class="fmt-btn" data-format="png">
              <span class="fmt-badge">PNG</span>
              <span class="fmt-desc">Lossless</span>
            </button>
            <button class="fmt-btn" data-format="webp">
              <span class="fmt-badge">WebP</span>
              <span class="fmt-desc">Modern, efisien</span>
            </button>
          </div>
        </div>

        <!-- Convert Button -->
        <button class="btn-convert" id="btnConvert" disabled>
          <span class="btn-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/>
              <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/>
            </svg>
          </span>
          <span id="btnLabel">Pilih gambar dulu</span>
        </button>

      </div>

      <!-- Result Card (hidden by default) -->
      <div class="card result-card" id="resultCard" style="display:none;">
        <div class="result-header">
          <div class="result-icon success">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
              <polyline points="20 6 9 17 4 12"/>
            </svg>
          </div>
          <div>
            <h2 class="result-title">Konversi Berhasil!</h2>
            <p class="result-sub" id="resultSub">—</p>
          </div>
        </div>

        <div class="result-stats" id="resultStats">
          <div class="stat-item">
            <span class="stat-label">File Asli</span>
            <span class="stat-val" id="statOrigName">—</span>
          </div>
          <div class="stat-item">
            <span class="stat-label">Ukuran Asli</span>
            <span class="stat-val" id="statOrigSize">—</span>
          </div>
          <div class="stat-divider">→</div>
          <div class="stat-item">
            <span class="stat-label">File Hasil</span>
            <span class="stat-val" id="statConvName">—</span>
          </div>
          <div class="stat-item">
            <span class="stat-label">Ukuran Baru</span>
            <span class="stat-val" id="statConvSize">—</span>
          </div>
          <div class="stat-item compression">
            <span class="stat-label">Kompresi</span>
            <span class="stat-val" id="statCompression">—</span>
          </div>
        </div>

        <a class="btn-download" id="btnDownload" href="#" target="_blank">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
            <polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
          </svg>
          Download Hasil
        </a>
      </div>

      <!-- Error Card (hidden by default) -->
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
            <li>Pastikan server Azkiya nyala dan Apache running di XAMPP</li>
            <li>Cek IP server di <code>config.php</code> sudah benar</li>
            <li>Coba ping IP server dari CMD dulu</li>
            <li>Pastikan port 80 tidak diblokir firewall Windows</li>
          </ul>
        </div>
      </div>

      <!-- Architecture Info -->
      <div class="arch-info">
        <div class="arch-node">
          <div class="node-dot client-dot"></div>
          <div class="node-info">
            <span class="node-label">Node 1 — Client</span>
            <span class="node-detail">Browser + PHP Server (localhost:3000)</span>
          </div>
        </div>
        <div class="arch-arrow">
          <div class="arrow-line"></div>
          <span class="arrow-label">HTTP · LAN</span>
        </div>
        <div class="arch-node">
          <div class="node-dot server-dot"></div>
          <div class="node-info">
            <span class="node-label">Node 2 — Server</span>
            <span class="node-detail">PHP + MySQL (<?= SERVER_IP ?>:<?= SERVER_PORT ?>)</span>
          </div>
        </div>
      </div>

    </main>
  </div>

  <!-- Loading Overlay -->
  <div class="loading-overlay" id="loadingOverlay" style="display:none;">
    <div class="loading-box">
      <div class="spinner"></div>
      <p class="loading-text">Mengirim ke server...</p>
      <p class="loading-sub" id="loadingSub">Memproses gambar via GD Library</p>
    </div>
  </div>

  <script src="assets/app.js"></script>
</body>
</html>
