<?php

/**
 * download_proxy.php — File Download Proxy
 *
 * Browser request ke sini (localhost), PHP fetch file dari server
 * via cURL dengan header ngrok-skip-browser-warning, lalu stream
 * balik ke browser sebagai attachment download.
 *
 * Usage: /download_proxy.php?file=nama_file.webp
 */

require_once __DIR__ . '/config.php';

// =============================================
//  VALIDASI PARAM
// =============================================

$filename = $_GET['file'] ?? '';

if (empty($filename)) {
    http_response_code(400);
    die('Parameter "file" tidak ada.');
}

// basename() sudah handle path traversal — cukup block null byte
$filename = basename($filename);
if (str_contains($filename, "\0") || $filename === '') {
    http_response_code(400);
    die('Nama file tidak valid.');
}

// Cek ekstensi file (hanya format gambar yang diizinkan)
$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
    http_response_code(400);
    die('Tipe file tidak diizinkan.');
}

// =============================================
//  BUILD URL FILE DI SERVER
// =============================================

// Encode spasi di nama file agar URL valid
$encodedFilename = rawurlencode($filename);

if (CONNECTION_MODE === 'ngrok') {
    $fileUrl = NGROK_BASE_URL . '/gokil-conversion/server/uploads/' . $encodedFilename;
} else {
    $fileUrl = 'http://' . LOCAL_SERVER_IP . ':' . LOCAL_SERVER_PORT . '/gokil-conversion/server/uploads/' . $encodedFilename;
}

// =============================================
//  FETCH FILE VIA CURL
// =============================================

$ch = curl_init($fileUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => CURL_TIMEOUT_SEC,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTPHEADER     => [
        'ngrok-skip-browser-warning: true',  // Bypass ngrok warning page
        'User-Agent: GC-DownloadProxy/1.0',
    ],
]);

$fileData  = curl_exec($ch);
$httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
// curl_close() dihapus — CurlHandle otomatis di-free saat out of scope (PHP 8.0+)


if ($fileData === false || !empty($curlError)) {
    http_response_code(502);
    die("Gagal fetch file dari server: $curlError");
}

if ($httpCode !== 200) {
    http_response_code(502);
    die("Server merespons HTTP $httpCode untuk file yang diminta.");
}

// =============================================
//  STREAM KE BROWSER SEBAGAI DOWNLOAD
// =============================================

$safeMime = match($ext) {
    'jpg', 'jpeg' => 'image/jpeg',
    'png'         => 'image/png',
    'webp'        => 'image/webp',
    default       => 'application/octet-stream',
};

header('Content-Type: ' . $safeMime);
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . strlen($fileData));
header('Cache-Control: no-cache, no-store, must-revalidate');

echo $fileData;
