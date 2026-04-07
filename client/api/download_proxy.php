<?php

/**
 * download_proxy.php — File Download Proxy
 *
 * Browser request ke sini (localhost), PHP fetch file dari server
 * via cURL dengan header ngrok-skip-browser-warning, lalu stream
 * balik ke browser sebagai attachment download.
 *
 * Usage: /api/download_proxy.php?file=nama_file.webp
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../lib/curl_client.php';

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

$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
    http_response_code(400);
    die('Tipe file tidak diizinkan.');
}

// =============================================
//  BUILD URL FILE DI SERVER
// =============================================

$encodedFilename = rawurlencode($filename);

$fileUrl = CONNECTION_MODE === 'ngrok'
    ? NGROK_BASE_URL . '/gokil-conversion/server/uploads/' . $encodedFilename
    : 'http://' . LOCAL_SERVER_IP . ':' . LOCAL_SERVER_PORT . '/gokil-conversion/server/uploads/' . $encodedFilename;

// =============================================
//  FETCH & STREAM FILE
// =============================================

$result = curlGet(
    url: $fileUrl,
    headers: [
        'ngrok-skip-browser-warning: true',
        'User-Agent: GC-DownloadProxy/1.0',
    ],
    timeout: CURL_TIMEOUT_SEC
);

if ($result['body'] === false || !empty($result['error'])) {
    http_response_code(502);
    die("Gagal fetch file dari server: {$result['error']}");
}

if ($result['status'] !== 200) {
    http_response_code(502);
    die("Server merespons HTTP {$result['status']} untuk file yang diminta.");
}

$safeMime = match($ext) {
    'jpg', 'jpeg' => 'image/jpeg',
    'png'         => 'image/png',
    'webp'        => 'image/webp',
    default       => 'application/octet-stream',
};

header('Content-Type: ' . $safeMime);
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . strlen($result['body']));
header('Cache-Control: no-cache, no-store, must-revalidate');

echo $result['body'];
