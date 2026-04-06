/**
 * app.js — Gokil Conversion Client Logic
 * 
 * Flow:
 *  1. User drag/drop atau pilih file → preview
 *  2. User pilih target format
 *  3. Click convert → POST ke upload_handler.php (PHP proxy di localhost)
 *  4. PHP proxy forward ke server Azkiya via cURL
 *  5. Tampilkan result atau error
 */

const dropZone      = document.getElementById('dropZone');
const dropZoneInner = document.getElementById('dropZoneInner');
const previewZone   = document.getElementById('previewZone');
const previewImg    = document.getElementById('previewImg');
const previewName   = document.getElementById('previewName');
const previewSize   = document.getElementById('previewSize');
const fileInput     = document.getElementById('fileInput');
const btnClear      = document.getElementById('btnClear');
const formatOptions = document.getElementById('formatOptions');
const btnConvert    = document.getElementById('btnConvert');
const btnLabel      = document.getElementById('btnLabel');
const loadingOverlay = document.getElementById('loadingOverlay');
const loadingSub    = document.getElementById('loadingSub');
const resultCard    = document.getElementById('resultCard');
const errorCard     = document.getElementById('errorCard');

// Result fields
const resultSub     = document.getElementById('resultSub');
const statOrigName  = document.getElementById('statOrigName');
const statOrigSize  = document.getElementById('statOrigSize');
const statConvName  = document.getElementById('statConvName');
const statConvSize  = document.getElementById('statConvSize');
const statCompression = document.getElementById('statCompression');
const btnDownload   = document.getElementById('btnDownload');
const errorMsg      = document.getElementById('errorMsg');

let selectedFile   = null;
let selectedFormat = 'jpg';

// =============================================
//  UTILS
// =============================================

function formatBytes(bytes) {
  if (bytes >= 1048576) return (bytes / 1048576).toFixed(2) + ' MB';
  if (bytes >= 1024)    return (bytes / 1024).toFixed(1) + ' KB';
  return bytes + ' B';
}

function showElement(el)  { el.style.display = ''; }
function hideElement(el)  { el.style.display = 'none'; }

// =============================================
//  FILE SELECTION & PREVIEW
// =============================================

function setFile(file) {
  if (!file) return;

  const allowed = ['image/jpeg', 'image/png', 'image/webp'];
  if (!allowed.includes(file.type)) {
    showError('Format file tidak didukung. Gunakan JPG, PNG, atau WebP.');
    return;
  }

  const maxSize = 10 * 1024 * 1024;
  if (file.size > maxSize) {
    showError(`File terlalu besar (${formatBytes(file.size)}). Maks 10 MB.`);
    return;
  }

  selectedFile = file;

  // Buat preview
  const reader = new FileReader();
  reader.onload = (e) => {
    previewImg.src = e.target.result;
    previewName.textContent = file.name;
    previewSize.textContent = formatBytes(file.size);
    hideElement(dropZoneInner);
    showElement(previewZone);
    updateConvertButton();
  };
  reader.readAsDataURL(file);

  // Sembunyiin result/error cards sebelumnya
  hideElement(resultCard);
  hideElement(errorCard);
}

function clearFile() {
  selectedFile = null;
  fileInput.value = '';
  previewImg.src = '';
  hideElement(previewZone);
  showElement(dropZoneInner);
  updateConvertButton();
}

function updateConvertButton() {
  if (selectedFile) {
    btnConvert.disabled = false;
    btnLabel.textContent = `Convert ke ${selectedFormat.toUpperCase()}`;
  } else {
    btnConvert.disabled = true;
    btnLabel.textContent = 'Pilih gambar dulu';
  }
}

// =============================================
//  EVENT LISTENERS — FILE INPUT
// =============================================

fileInput.addEventListener('change', () => {
  if (fileInput.files.length > 0) setFile(fileInput.files[0]);
});

btnClear.addEventListener('click', (e) => {
  e.stopPropagation();
  clearFile();
});

// Click pada drop zone buka file picker
dropZone.addEventListener('click', (e) => {
  // Jangan trigger kalau klik tombol clear
  if (e.target === btnClear || btnClear.contains(e.target)) return;
  if (!selectedFile) fileInput.click();
});

// =============================================
//  DRAG & DROP
// =============================================

['dragenter', 'dragover'].forEach(evt => {
  dropZone.addEventListener(evt, (e) => {
    e.preventDefault();
    dropZone.classList.add('drag-over');
  });
});

['dragleave', 'dragend', 'drop'].forEach(evt => {
  dropZone.addEventListener(evt, (e) => {
    e.preventDefault();
    dropZone.classList.remove('drag-over');
  });
});

dropZone.addEventListener('drop', (e) => {
  const files = e.dataTransfer.files;
  if (files.length > 0) setFile(files[0]);
});

// =============================================
//  FORMAT SELECTOR
// =============================================

formatOptions.addEventListener('click', (e) => {
  const btn = e.target.closest('.fmt-btn');
  if (!btn) return;

  document.querySelectorAll('.fmt-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  selectedFormat = btn.dataset.format;
  updateConvertButton();
});

// =============================================
//  CONVERT — KIRIM KE PHP PROXY
// =============================================

btnConvert.addEventListener('click', async () => {
  if (!selectedFile) return;

  hideElement(resultCard);
  hideElement(errorCard);
  showElement(loadingOverlay);
  loadingSub.textContent = 'Mengirim file ke server Azkiya...';

  const formData = new FormData();
  formData.append('image', selectedFile, selectedFile.name);
  formData.append('target_format', selectedFormat);

  try {
    const res = await fetch('upload_handler.php', {
      method: 'POST',
      body: formData,
    });

    // Update loading text
    loadingSub.textContent = 'Memproses via GD Library...';

    if (!res.ok) {
      throw new Error(`HTTP ${res.status}: Server proxy merespons dengan error.`);
    }

    const data = await res.json();

    hideElement(loadingOverlay);

    if (data.success) {
      showResult(data.data);
    } else {
      showError(data.message || 'Error tidak diketahui dari server.');
    }

  } catch (err) {
    hideElement(loadingOverlay);
    showError(err.message || 'Gagal terhubung ke server. Cek konsol browser untuk detail.');
    console.error('[Gokil Conversion]', err);
  }
});

// =============================================
//  SHOW RESULT / ERROR
// =============================================

function showResult(data) {
  resultSub.textContent = `${data.original_format.toUpperCase()} → ${data.target_format.toUpperCase()} berhasil diproses di server.`;
  statOrigName.textContent  = data.original_filename;
  statOrigSize.textContent  = formatBytes(data.original_size);
  statConvName.textContent  = data.converted_filename;
  statConvSize.textContent  = formatBytes(data.converted_size);

  const ratio = parseFloat(data.compression_ratio);
  if (ratio > 0) {
    statCompression.textContent = `-${ratio}% lebih kecil`;
  } else if (ratio < 0) {
    statCompression.textContent = `+${Math.abs(ratio)}% lebih besar`;
  } else {
    statCompression.textContent = 'Sama';
  }

  // Arahkan ke proxy lokal (localhost) bukan langsung ke ngrok
  // Ini bypass ngrok browser warning dan langsung trigger download
  const proxyUrl = `download_proxy.php?file=${encodeURIComponent(data.converted_filename)}`;
  btnDownload.href = proxyUrl;
  btnDownload.removeAttribute('target'); // jangan buka tab baru

  showElement(resultCard);

  // Scroll ke result
  resultCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
}


function showError(message) {
  errorMsg.textContent = message;
  showElement(errorCard);
  errorCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
}
