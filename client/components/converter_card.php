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
