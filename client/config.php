<?php

/**
 * config.php — Client Configuration
 * 
 * Ganti SERVER_IP dengan IP laptop Azkiya di jaringan lokal.
 * Cara cari IP Azkiya: CMD → ipconfig → IPv4 Address
 */

// =============================================
//  NETWORK CONFIG — WAJIB DIUPDATE SEBELUM DEMO
// =============================================
define('SERVER_IP',       '192.168.1.100');
define('SERVER_PORT',     80);
define('SERVER_ENDPOINT', "http://" . SERVER_IP . ":" . SERVER_PORT . "/server/api/upload.php");

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
