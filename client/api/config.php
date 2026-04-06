<?php

/**
 * config.php — Client Configuration
 *
 * Semua config dibaca dari file .env di folder ini.
 * Cara setup: copy .env.example → .env, lalu isi nilainya.
 * Cara jalankan: php -S localhost:3000 (dari folder /client)
 */

// =============================================
//  ENV LOADER — parser .env sederhana
// =============================================

$envPath = __DIR__ . '/../.env';  // .env ada di /client/, satu level di atas /api/


if (!file_exists($envPath)) {
    die(
        "<pre style='font-family:monospace;padding:20px;background:#1a1a2e;color:#ef4444;'>" .
        "⚠️  File .env tidak ditemukan!\n\n" .
        "Jalankan perintah ini di folder /client:\n" .
        "  cp .env.example .env\n\n" .
        "Lalu edit .env sesuai kebutuhan.\n" .
        "</pre>"
    );
}

foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
    $line = trim($line);
    if ($line === '' || str_starts_with($line, '#')) continue;
    if (!str_contains($line, '=')) continue;
    [$key, $val] = explode('=', $line, 2);
    $_ENV[trim($key)] = trim($val);
}

// =============================================
//  RESOLVE CONFIG DARI ENV
//  Nilai dari $_ENV harus tetap pakai define() — bukan const.
//  Alasan: const butuh compile-time value, sedangkan $_ENV dibaca runtime.
// =============================================

$mode = $_ENV['CONNECTION_MODE'] ?? 'local';
define('CONNECTION_MODE', $mode);

// Mode LOCAL
define('LOCAL_SERVER_IP',   $_ENV['LOCAL_SERVER_IP']   ?? '192.168.1.100');
define('LOCAL_SERVER_PORT', (int)($_ENV['LOCAL_SERVER_PORT'] ?? 80));
define('LOCAL_ENDPOINT',    'http://' . LOCAL_SERVER_IP . ':' . LOCAL_SERVER_PORT . '/gokil-conversion/server/api/upload.php');

// Mode NGROK
define('NGROK_BASE_URL', rtrim($_ENV['NGROK_BASE_URL'] ?? '', '/'));
define('NGROK_ENDPOINT', NGROK_BASE_URL . '/gokil-conversion/server/api/upload.php');

// Endpoint aktif
define('SERVER_ENDPOINT', $mode === 'ngrok' ? NGROK_ENDPOINT : LOCAL_ENDPOINT);

// Label untuk UI
define('ACTIVE_SERVER_LABEL',
    $mode === 'ngrok'
        ? 'Ngrok · ' . NGROK_BASE_URL
        : 'LAN · ' . LOCAL_SERVER_IP . ':' . LOCAL_SERVER_PORT
);

// =============================================
//  FILE VALIDATION
//  Nilai literal statis → pakai const (compile-time safe)
// =============================================
define('MAX_FILE_SIZE_MB', (int)($_ENV['MAX_FILE_SIZE_MB'] ?? 10));
define('MAX_FILE_SIZE',    MAX_FILE_SIZE_MB * 1024 * 1024);

const ALLOWED_INPUT_FORMATS  = ['jpg', 'jpeg', 'png', 'webp'];
const ALLOWED_OUTPUT_FORMATS = ['jpg', 'png', 'webp'];

// =============================================
//  CURL TIMEOUT
// =============================================
define('CURL_TIMEOUT_SEC', (int)($_ENV['CURL_TIMEOUT_SEC'] ?? ($mode === 'ngrok' ? 60 : 30)));
