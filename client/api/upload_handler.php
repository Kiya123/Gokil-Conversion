<?php

/**
 * upload_handler.php — PHP Proxy
 *
 * Menerima FormData dari browser (app.js),
 * lalu forward ke server Azkiya pakai cURL.
 * Ini yang bikin Node 1 tetap "berperan" secara teknis —
 * bukan cuma static file server biasa.
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../lib/curl_client.php';

header('Content-Type: application/json; charset=UTF-8');

// =============================================
//  VALIDASI REQUEST
// =============================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

if (empty($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'File tidak diterima oleh proxy.']);
    exit;
}

if (empty($_POST['target_format'])) {
    echo json_encode(['success' => false, 'message' => 'target_format tidak ada.']);
    exit;
}

$targetFormat = strtolower(trim($_POST['target_format']));
$uploadedFile = $_FILES['image'];

if ($uploadedFile['size'] > MAX_FILE_SIZE) {
    $sizeMB = round($uploadedFile['size'] / 1048576, 2);
    echo json_encode(['success' => false, 'message' => "File terlalu besar ({$sizeMB} MB). Maks: " . MAX_FILE_SIZE_MB . " MB."]);
    exit;
}

if (!in_array($targetFormat, ALLOWED_OUTPUT_FORMATS)) {
    echo json_encode(['success' => false, 'message' => "Format tujuan '$targetFormat' tidak valid."]);
    exit;
}

// =============================================
//  FORWARD KE SERVER AZKIYA VIA CURL
// =============================================

$result = curlPost(
    url: SERVER_ENDPOINT,
    postData: [
        'image'         => new CURLFile($uploadedFile['tmp_name'], $uploadedFile['type'], $uploadedFile['name']),
        'target_format' => $targetFormat,
    ],
    headers: [
        'X-Client-Type: php-proxy',
        'X-Client-Version: 1.0',
        'X-Forwarded-From: ' . ($_SERVER['REMOTE_ADDR'] ?? 'browser'),
    ],
    timeout: CURL_TIMEOUT_SEC
);

// =============================================
//  HANDLE RESPONSE DARI SERVER
// =============================================

if ($result['body'] === false) {
    echo json_encode([
        'success' => false,
        'message' => "Proxy gagal konek ke server Azkiya ({$result['error']}). Pastikan server nyala dan IP di config sudah benar."
    ]);
    exit;
}

if ($result['status'] !== 200) {
    echo json_encode([
        'success' => false,
        'message' => "Server Azkiya merespons HTTP {$result['status']}. Cek Apache/XAMPP di laptop Azkiya."
    ]);
    exit;
}

// Terusin response JSON server langsung ke browser
echo $result['body'];
