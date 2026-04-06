<?php

/**
 * config.php — Server Configuration
 *
 * Semua config dibaca dari file .env di folder ini.
 * Cara setup: copy .env.example → .env, lalu isi nilainya.
 */

// =============================================
//  ENV LOADER — parser .env sederhana
// =============================================

$envPath = __DIR__ . '/.env';

if (!file_exists($envPath)) {
    http_response_code(500);
    header('Content-Type: application/json');
    die(json_encode([
        'success' => false,
        'message' => 'Server misconfigured: file .env tidak ditemukan. Jalankan: cp .env.example .env'
    ]));
}

foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
    $line = trim($line);
    if ($line === '' || str_starts_with($line, '#')) continue;
    if (!str_contains($line, '=')) continue;
    [$key, $val] = explode('=', $line, 2);
    $_ENV[trim($key)] = trim($val);
}

// =============================================
//  DATABASE CONFIG
// =============================================
define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_PORT', (int)($_ENV['DB_PORT'] ?? 3306));
define('DB_NAME', $_ENV['DB_NAME'] ?? 'gokil_conversion');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');

// =============================================
//  FILE STORAGE
// =============================================

define('UPLOAD_DIR', __DIR__ . '/uploads/');

// Resolve UPLOAD_URL_BASE berdasarkan mode koneksi
$mode = $_ENV['CONNECTION_MODE'] ?? 'local';
define('CONNECTION_MODE', $mode);

if ($mode === 'ngrok') {
    $ngrokUrl = rtrim($_ENV['NGROK_URL'] ?? '', '/');
    define('UPLOAD_URL_BASE', $ngrokUrl . '/gokil-conversion/server/uploads/');
} else {
    define('UPLOAD_URL_BASE', rtrim($_ENV['LOCAL_UPLOAD_URL'] ?? 'http://localhost/gokil-conversion/server/uploads/', '/') . '/');
}

// =============================================
//  FILE VALIDATION
// =============================================
define('MAX_FILE_SIZE_MB', (int)($_ENV['MAX_FILE_SIZE_MB'] ?? 10));
define('MAX_FILE_SIZE',    MAX_FILE_SIZE_MB * 1024 * 1024);

define('ALLOWED_INPUT_FORMATS',  ['jpg', 'jpeg', 'png', 'webp']);
define('ALLOWED_OUTPUT_FORMATS', ['jpg', 'png', 'webp']);

define('MIME_TO_EXT', [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/webp' => 'webp',
]);
