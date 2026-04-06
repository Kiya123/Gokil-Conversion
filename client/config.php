<?php

/**
 * config.php — Client Configuration
 *
 * ┌─────────────────────────────────────────────────────┐
 * │              TOGGLE MODE KONEKSI                     │
 * │                                                      │
 * │  'local'  → LAN via hotspot HP (IP langsung)         │
 * │  'ngrok'  → Ngrok tunnel (HTTPS publik)              │
 * └─────────────────────────────────────────────────────┘
 *
 * Cara jalankan client: php -S localhost:3000 (dari folder /client)
 */

// =============================================
//  ★ TOGGLE DI SINI — 'local' atau 'ngrok'
// =============================================
define('CONNECTION_MODE', 'ngrok');

// =============================================
//  MODE 1: LOCAL — LAN via Hotspot HP
//  Pastikan kedua laptop konek ke HP yang sama.
//  Cara cari IP Azkiya: CMD → ipconfig → IPv4 Address
// =============================================
define('LOCAL_SERVER_IP',   '192.168.1.100');  // ← Ganti sesuai IP laptop Azkiya
define('LOCAL_SERVER_PORT', 80);
define('LOCAL_ENDPOINT',    'http://' . LOCAL_SERVER_IP . ':' . LOCAL_SERVER_PORT . '/server/api/upload.php');

// =============================================
//  MODE 2: NGROK — Tunnel HTTPS publik
//  Cara pakai:
//   1. Di laptop Azkiya: ngrok http 80
//   2. Copy URL yang muncul (misal: https://xxxx-xx-xx.ngrok-free.app)
//   3. Paste ke NGROK_BASE_URL di bawah (tanpa trailing slash)
// =============================================
define('NGROK_BASE_URL',  'https://b77b-140-213-10-149.ngrok-free.app');  // ← Ganti tiap sesi ngrok baru
define('NGROK_ENDPOINT',  NGROK_BASE_URL . '/gokil-conversion/server/api/upload.php');

// =============================================
//  RESOLVE ENDPOINT AKTIF (jangan diubah)
// =============================================
define('SERVER_ENDPOINT', CONNECTION_MODE === 'ngrok' ? NGROK_ENDPOINT : LOCAL_ENDPOINT);

// Untuk ditampilkan di UI (index.php)
define('ACTIVE_SERVER_LABEL',
    CONNECTION_MODE === 'ngrok'
        ? 'Ngrok · ' . NGROK_BASE_URL
        : 'LAN · ' . LOCAL_SERVER_IP . ':' . LOCAL_SERVER_PORT
);

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
// Ngrok agak lebih lambat karena lewat internet, kasih slack lebih
define('CURL_TIMEOUT_SEC', CONNECTION_MODE === 'ngrok' ? 60 : 30);
