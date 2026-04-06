/** utils.js — Shared Utility Functions */

function formatBytes(bytes) {
  if (bytes >= 1048576) return (bytes / 1048576).toFixed(2) + ' MB';
  if (bytes >= 1024)    return (bytes / 1024).toFixed(1) + ' KB';
  return bytes + ' B';
}

function showElement(el) { el.style.display = ''; }
function hideElement(el) { el.style.display = 'none'; }
