/** convert.js — Convert Button Handler & Fetch to PHP Proxy */

btnConvert.addEventListener('click', async () => {
  if (!selectedFile) return;

  hideElement(resultCard);
  hideElement(errorCard);
  showElement(loadingOverlay);
  loadingSub.textContent = 'Mengirim file ke server Dier...';

  const formData = new FormData();
  formData.append('image', selectedFile, selectedFile.name);
  formData.append('target_format', selectedFormat);

  try {
    const res = await fetch('api/upload_handler.php', {
      method: 'POST',
      body: formData,
    });

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
