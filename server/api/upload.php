<?php

/**
 * upload.php — Main API Endpoint
 * 
 * Menerima POST request dari PHP CLI client,
 * mengkonversi gambar pakai GD Library,
 * menyimpan hasil, dan logging ke MySQL.
 * 
 * Method : POST
 * Fields : image (file), target_format (string)
 * Returns: JSON
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../db/connection.php';

// =============================================
//  CORS HEADERS — Wajib buat izinin client dari IP lain
// =============================================
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: X-Client-Type, X-Client-Version');

// Preflight request (browser/curl OPTIONS check)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// =============================================
//  HELPERS
// =============================================

function jsonSuccess(array $data): void {
    echo json_encode(['success' => true, 'data' => $data]);
    exit;
}

function jsonError(string $message, int $httpCode = 400): void {
    http_response_code($httpCode);
    echo json_encode(['success' => false, 'message' => $message]);
    exit;
}

// =============================================
//  VALIDASI METHOD & INPUT
// =============================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonError('Hanya menerima POST request.', 405);
}

if (empty($_FILES['image'])) {
    jsonError('Field "image" tidak ada dalam request.');
}

if (empty($_POST['target_format'])) {
    jsonError('Field "target_format" tidak ada dalam request.');
}

$uploadedFile  = $_FILES['image'];
$targetFormat  = strtolower(trim($_POST['target_format']));

// Cek upload error dari PHP
if ($uploadedFile['error'] !== UPLOAD_ERR_OK) {
    $phpUploadErrors = [
        UPLOAD_ERR_INI_SIZE   => 'File melebihi upload_max_filesize di php.ini',
        UPLOAD_ERR_FORM_SIZE  => 'File melebihi MAX_FILE_SIZE di form',
        UPLOAD_ERR_PARTIAL    => 'File hanya ter-upload sebagian',
        UPLOAD_ERR_NO_FILE    => 'Tidak ada file yang di-upload',
        UPLOAD_ERR_NO_TMP_DIR => 'Folder temporary tidak ditemukan',
        UPLOAD_ERR_CANT_WRITE => 'Gagal menulis file ke disk',
        UPLOAD_ERR_EXTENSION  => 'Upload dihentikan oleh ekstensi PHP',
    ];
    $errMsg = $phpUploadErrors[$uploadedFile['error']] ?? 'Unknown upload error';
    jsonError("Upload gagal: $errMsg");
}

// Cek ukuran file
if ($uploadedFile['size'] > MAX_FILE_SIZE) {
    $sizeMB = round($uploadedFile['size'] / 1048576, 2);
    jsonError("File terlalu besar ({$sizeMB} MB). Maks: " . MAX_FILE_SIZE_MB . " MB.");
}

// Cek format output valid
if (!in_array($targetFormat, ALLOWED_OUTPUT_FORMATS)) {
    jsonError("Format tujuan '$targetFormat' tidak didukung. Pilihan: " . implode(', ', ALLOWED_OUTPUT_FORMATS));
}

// Validasi MIME type gambar (lebih aman dari cek ekstensi)
$mimeType = mime_content_type($uploadedFile['tmp_name']);
$mimeMap  = MIME_TO_EXT;
if (!array_key_exists($mimeType, $mimeMap)) {
    jsonError("File bukan gambar yang didukung. MIME terdeteksi: $mimeType");
}

$originalExt  = $mimeMap[$mimeType];
$originalSize = $uploadedFile['size'];
$originalName = $uploadedFile['name'];
$tmpPath      = $uploadedFile['tmp_name'];

// =============================================
//  PASTIKAN FOLDER UPLOADS ADA
// =============================================

if (!is_dir(UPLOAD_DIR)) {
    if (!mkdir(UPLOAD_DIR, 0755, true)) {
        jsonError('Gagal membuat folder uploads di server.', 500);
    }
}

// =============================================
//  LOAD GAMBAR DENGAN GD LIBRARY
// =============================================

$sourceImage = match($mimeType) {
    'image/jpeg' => imagecreatefromjpeg($tmpPath),
    'image/png'  => imagecreatefrompng($tmpPath),
    'image/webp' => imagecreatefromwebp($tmpPath),
    default      => null,
};

if ($sourceImage === false || $sourceImage === null) {
    jsonError('GD Library gagal membaca gambar.', 500);
}

// Preserve alpha channel untuk PNG dan WebP
if ($originalExt === 'png' || $mimeType === 'image/png') {
    imagealphablending($sourceImage, false);
    imagesavealpha($sourceImage, true);
}

// =============================================
//  KONVERSI & SIMPAN
// =============================================

// Generate nama file output yang unik
$baseName       = pathinfo($originalName, PATHINFO_FILENAME);
$timestamp      = date('Ymd_His');
$uniqueId       = substr(uniqid(), -6);
$convertedName  = "{$baseName}_{$timestamp}_{$uniqueId}.{$targetFormat}";
$outputPath     = UPLOAD_DIR . $convertedName;

$saveResult = match($targetFormat) {
    'jpg'  => imagejpeg($sourceImage, $outputPath, 85),  // quality 85 — balance antara size & kualitas
    'png'  => imagepng($sourceImage, $outputPath, 6),    // compression level 6 (0-9)
    'webp' => imagewebp($sourceImage, $outputPath, 80),  // quality 80
    default => false,
};

// imagedestroy() dihapus — GdImage di-free otomatis saat out of scope (PHP 8.0+)


if (!$saveResult) {
    jsonError('GD Library gagal menyimpan file hasil konversi.', 500);
}

$convertedSize = filesize($outputPath);
$compressionRatio = $originalSize > 0
    ? round((1 - $convertedSize / $originalSize) * 100, 1)
    : 0;

$downloadUrl = UPLOAD_URL_BASE . $convertedName;

// =============================================
//  LOG KE DATABASE
// =============================================

$logId = null;
try {
    $db = getDB();
    $stmt = $db->prepare("
        INSERT INTO conversion_logs
            (original_filename, converted_filename, original_format, target_format,
             original_size, converted_size, client_ip, download_url)
        VALUES
            (:orig_name, :conv_name, :orig_fmt, :tgt_fmt,
             :orig_size, :conv_size, :client_ip, :dl_url)
    ");
    $stmt->execute([
        ':orig_name'  => $originalName,
        ':conv_name'  => $convertedName,
        ':orig_fmt'   => $originalExt,
        ':tgt_fmt'    => $targetFormat,
        ':orig_size'  => $originalSize,
        ':conv_size'  => $convertedSize,
        ':client_ip'  => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        ':dl_url'     => $downloadUrl,
    ]);
    $logId = $db->lastInsertId();
} catch (PDOException $e) {
    // DB error tidak blokir response — file udah jadi, tetap return sukses
    // Cukup catat di error log server
    error_log('[GC] DB logging failed: ' . $e->getMessage());
}

// =============================================
//  RETURN JSON RESPONSE
// =============================================

jsonSuccess([
    'log_id'             => $logId,
    'original_filename'  => $originalName,
    'original_format'    => $originalExt,
    'original_size'      => $originalSize,
    'converted_filename' => $convertedName,
    'target_format'      => $targetFormat,
    'converted_size'     => $convertedSize,
    'compression_ratio'  => $compressionRatio,
    'download_url'       => $downloadUrl,
]);
