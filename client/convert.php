#!/usr/bin/env php
<?php

/**
 * convert.php — Gokil Conversion Client
 * 
 * Usage:
 *   php convert.php <path_ke_gambar> <format_tujuan>
 * 
 * Contoh:
 *   php convert.php foto.jpg png
 *   php convert.php /home/dier/gambar.png webp
 * 
 * Format yang didukung: jpg, png, webp
 */

require_once __DIR__ . '/config.php';

// =============================================
//  HELPER FUNCTIONS
// =============================================

function printLine(string $msg): void {
    echo $msg . PHP_EOL;
}

function printSuccess(string $msg): void {
    echo "\033[32m✓ " . $msg . "\033[0m" . PHP_EOL;
}

function printError(string $msg): void {
    echo "\033[31m✗ ERROR: " . $msg . "\033[0m" . PHP_EOL;
}

function printInfo(string $msg): void {
    echo "\033[36mℹ " . $msg . "\033[0m" . PHP_EOL;
}

function formatBytes(int $bytes): string {
    if ($bytes >= 1048576) return round($bytes / 1048576, 2) . " MB";
    if ($bytes >= 1024)    return round($bytes / 1024, 2) . " KB";
    return $bytes . " B";
}

// =============================================
//  ARGUMENT PARSING
// =============================================

// $argv[0] = nama script, [1] = path file, [2] = format tujuan
if ($argc < 3) {
    printLine("╔════════════════════════════════════════╗");
    printLine("║     Gokil Conversion — PHP Client      ║");
    printLine("╚════════════════════════════════════════╝");
    printLine("");
    printLine("Usage:   php convert.php <file> <target_format>");
    printLine("Contoh:  php convert.php foto.jpg png");
    printLine("");
    printLine("Format didukung: " . implode(', ', ALLOWED_OUTPUT_FORMATS));
    exit(1);
}

$inputPath    = $argv[1];
$targetFormat = strtolower(trim($argv[2]));

// =============================================
//  VALIDASI INPUT
// =============================================

printLine("");
printLine("╔════════════════════════════════════════╗");
printLine("║     Gokil Conversion — PHP Client      ║");
printLine("╚════════════════════════════════════════╝");
printLine("");

// 1. Cek file ada
if (!file_exists($inputPath)) {
    printError("File tidak ditemukan: $inputPath");
    exit(1);
}

// 2. Resolve path absolut
$inputPath = realpath($inputPath);

// 3. Cek format input
$inputExt = strtolower(pathinfo($inputPath, PATHINFO_EXTENSION));
if (!in_array($inputExt, ALLOWED_INPUT_FORMATS)) {
    printError("Format input '$inputExt' tidak didukung.");
    printLine("         Format yang diterima: " . implode(', ', ALLOWED_INPUT_FORMATS));
    exit(1);
}

// 4. Cek format output
if (!in_array($targetFormat, ALLOWED_OUTPUT_FORMATS)) {
    printError("Format tujuan '$targetFormat' tidak didukung.");
    printLine("         Format yang tersedia: " . implode(', ', ALLOWED_OUTPUT_FORMATS));
    exit(1);
}

// 5. Cek ukuran file
$fileSize = filesize($inputPath);
if ($fileSize > MAX_FILE_SIZE) {
    printError("File terlalu besar: " . formatBytes($fileSize) . " (Maks: " . MAX_FILE_SIZE_MB . " MB)");
    exit(1);
}

// 6. Validasi file adalah gambar (bukan cuma cek ekstensi)
$imageInfo = @getimagesize($inputPath);
if ($imageInfo === false) {
    printError("File bukan gambar yang valid.");
    exit(1);
}

$fileName = basename($inputPath);
printInfo("File     : $fileName");
printInfo("Ukuran   : " . formatBytes($fileSize));
printInfo("Format   : $inputExt → $targetFormat");
printInfo("Server   : " . SERVER_ENDPOINT);
printLine("");

// =============================================
//  KIRIM VIA CURL
// =============================================

printInfo("Mengirim file ke server...");

$curlFile = new CURLFile($inputPath, $imageInfo['mime'], $fileName);

$postData = [
    'image'         => $curlFile,
    'target_format' => $targetFormat,
];

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL            => SERVER_ENDPOINT,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $postData,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => CURL_TIMEOUT_SEC,
    // Header tambahan buat identifikasi request dari client PHP
    CURLOPT_HTTPHEADER     => [
        'X-Client-Type: php-cli',
        'X-Client-Version: 1.0',
    ],
]);

$response   = curl_exec($ch);
$httpCode   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError  = curl_error($ch);
$totalTime  = curl_getinfo($ch, CURLINFO_TOTAL_TIME);
curl_close($ch);

// =============================================
//  HANDLE RESPONSE
// =============================================

// Cek koneksi gagal (bukan HTTP error, tapi network error)
if ($response === false) {
    printError("Gagal konek ke server: $curlError");
    printLine("");
    printLine("Checklist:");
    printLine("  1. Pastikan server Azkiya nyala dan Apache jalan di XAMPP");
    printLine("  2. Pastiin IP di config.php sudah benar: " . SERVER_IP);
    printLine("  3. Coba ping " . SERVER_IP . " dari CMD dulu");
    printLine("  4. Cek firewall Windows di laptop Azkiya (port 80 harus terbuka)");
    exit(1);
}

// Cek HTTP status code
if ($httpCode !== 200) {
    printError("Server merespons dengan HTTP $httpCode");
    printLine("Raw response: $response");
    exit(1);
}

// Parse JSON response
$result = json_decode($response, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    printError("Response dari server bukan JSON yang valid.");
    printLine("Raw response: $response");
    exit(1);
}

// =============================================
//  TAMPILKAN HASIL
// =============================================

if ($result['success'] === true) {
    printLine("");
    printSuccess("Konversi berhasil!");
    printLine("");
    printLine("┌─────────────────────────────────────────");
    printLine("│  File Asli   : " . $result['data']['original_filename']);
    printLine("│  Ukuran Asli : " . formatBytes($result['data']['original_size']));
    printLine("│─────────────────────────────────────────");
    printLine("│  File Hasil  : " . $result['data']['converted_filename']);
    printLine("│  Ukuran Baru : " . formatBytes($result['data']['converted_size']));
    printLine("│  Kompresi    : " . $result['data']['compression_ratio'] . "%");
    printLine("│─────────────────────────────────────────");
    printLine("│  Download    : " . $result['data']['download_url']);
    printLine("│  Waktu Proses: " . round($totalTime, 2) . " detik");
    printLine("└─────────────────────────────────────────");
    printLine("");
} else {
    printError("Server gagal memproses: " . ($result['message'] ?? 'Unknown error'));
    exit(1);
}
