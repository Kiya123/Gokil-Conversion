<?php

/**
 * config.php — Server Configuration
 * 
 * File ini ada di laptop Azkiya (atau XAMPP lokal sementara).
 * Jangan push credential ini ke GitHub — idealnya pindah ke .env.
 */

// =============================================
//  DATABASE CONFIG (MySQL via XAMPP)
// =============================================
define('DB_HOST', 'localhost');
define('DB_PORT', 3306);
define('DB_NAME', 'gokil_conversion');
define('DB_USER', 'root');
define('DB_PASS', '');      // Default XAMPP kosong, ubah kalau udah di-set

// =============================================
//  FILE STORAGE
// =============================================

// Path absolut ke folder uploads (di dalam /server/)
define('UPLOAD_DIR', __DIR__ . '/uploads/');

// URL publik untuk akses file hasil konversi
// ⚠️ WAJIB DIUPDATE sesuai mode koneksi yang dipakai:
//   - LAN lokal : 'http://192.168.x.x/gokil-conversion/server/uploads/'
//   - Ngrok     : 'https://xxxx.ngrok-free.app/gokil-conversion/server/uploads/'
define("NGROK_URL", "https://70fd-140-213-10-149.ngrok-free.app");
define('UPLOAD_URL_BASE', NGROK_URL . '/gokil-conversion/server/uploads/');


// =============================================
//  FILE VALIDATION
// =============================================
define('MAX_FILE_SIZE_MB', 10);
define('MAX_FILE_SIZE',    MAX_FILE_SIZE_MB * 1024 * 1024);

define('ALLOWED_INPUT_FORMATS',  ['jpg', 'jpeg', 'png', 'webp']);
define('ALLOWED_OUTPUT_FORMATS', ['jpg', 'png', 'webp']);

// Mapping MIME type ke format
define('MIME_TO_EXT', [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/webp' => 'webp',
]);
