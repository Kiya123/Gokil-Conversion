<?php

/**
 * config.php — Client Configuration
 *
 * Load .env dan resolve semua konstanta yang dipakai client.
 * Cara setup: copy .env.example → .env, lalu isi nilainya.
 * Cara jalankan: php -S localhost:3000 (dari folder /client)
 */

require_once __DIR__ . '/../lib/env_loader.php';

loadEnv(__DIR__ . '/../.env');

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
