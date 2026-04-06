<?php

/**
 * config.php — Client Configuration
 *
 * Cara pakai:
 *   1. Ganti SERVER_IP dengan IP laptop Azkiya di jaringan lokal
 *   2. Cara cari IP Azkiya: CMD → ipconfig → Cari 'IPv4 Address'
 *   3. Jalankan client: php -S localhost:3000 (dari folder /client)
 */

// =============================================
//  NETWORK CONFIG — WAJIB DIUPDATE SEBELUM DEMO
// =============================================
define('SERVER_IP',       '127.0.0.1');  // Ganti ke IP Azkiya saat demo (misal: 192.168.1.100)
define('SERVER_PORT',     80);
define('SERVER_ENDPOINT', 'http://' . SERVER_IP . ':' . SERVER_PORT . '/server/api/upload.php');

// =============================================
//  FILE VALIDATION
// =============================================
define('MAX_FILE_SIZE_MB', 10);
define('MAX_FILE_SIZE',    MAX_FILE_SIZE_MB * 1024 * 1024); // dalam bytes

define('ALLOWED_INPUT_FORMATS',  ['jpg', 'jpeg', 'png', 'webp']);
define('ALLOWED_OUTPUT_FORMATS', ['jpg', 'png', 'webp']);

// =============================================
//  CURL TIMEOUT
// =============================================
define('CURL_TIMEOUT_SEC', 30);
