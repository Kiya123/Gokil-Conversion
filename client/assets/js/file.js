/** file.js — File Selection, Preview, Clear */

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

// Event listeners
fileInput.addEventListener('change', () => {
  if (fileInput.files.length > 0) setFile(fileInput.files[0]);
});

btnClear.addEventListener('click', (e) => {
  e.stopPropagation();
  clearFile();
});

dropZone.addEventListener('click', (e) => {
  if (e.target === btnClear || btnClear.contains(e.target)) return;
  if (!selectedFile) fileInput.click();
});
