/** ui.js — Show Result & Error Cards */

function showResult(data) {
  resultSub.textContent = `${data.original_format.toUpperCase()} → ${data.target_format.toUpperCase()} berhasil diproses di server.`;
  statOrigName.textContent  = data.original_filename;
  statOrigSize.textContent  = formatBytes(data.original_size);
  statConvName.textContent  = data.converted_filename;
  statConvSize.textContent  = formatBytes(data.converted_size);

  const ratio = parseFloat(data.compression_ratio);
  if (ratio > 0)       statCompression.textContent = `-${ratio}% lebih kecil`;
  else if (ratio < 0)  statCompression.textContent = `+${Math.abs(ratio)}% lebih besar`;
  else                 statCompression.textContent = 'Sama';

  // Proxy melalui PHP biar nggak kena ngrok browser warning
  btnDownload.href = `api/download_proxy.php?file=${encodeURIComponent(data.converted_filename)}`;
  btnDownload.removeAttribute('target');

  showElement(resultCard);
  resultCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function showError(message) {
  errorMsg.textContent = message;
  showElement(errorCard);
  errorCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
}
