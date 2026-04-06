<?php

/**
 * config.php — Server Configuration
 *
 * Load .env dan resolve semua konstanta yang dipakai server.
 * Cara setup: copy .env.example → .env, lalu isi nilainya.
 */

require_once __DIR__ . '/lib/env_loader.php';

loadEnv(__DIR__ . '/.env');

// =============================================
//  DATABASE CONFIG
//  Nilai dari $_ENV → harus pakai define() (runtime value, bukan compile-time)
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
//  Nilai literal statis → pakai const (compile-time safe)
// =============================================
define('MAX_FILE_SIZE_MB', (int)($_ENV['MAX_FILE_SIZE_MB'] ?? 10));
define('MAX_FILE_SIZE',    MAX_FILE_SIZE_MB * 1024 * 1024);

const ALLOWED_INPUT_FORMATS  = ['jpg', 'jpeg', 'png', 'webp'];
const ALLOWED_OUTPUT_FORMATS = ['jpg', 'png', 'webp'];

const MIME_TO_EXT = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/webp' => 'webp',
];
