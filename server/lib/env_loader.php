<?php

/**
 * env_loader.php — Minimal .env Parser (Server)
 *
 * Sama persis dengan versi client. Dipisah agar server
 * bisa berdiri sendiri di mesin berbeda (laptop Dier).
 *
 * Usage: loadEnv(__DIR__ . '/../.env');
 */

function loadEnv(string $path): void
{
    if (!file_exists($path)) {
        http_response_code(500);
        header('Content-Type: application/json');
        die(json_encode([
            'success' => false,
            'message' => "Server misconfigured: .env tidak ditemukan ($path). Jalankan: cp .env.example .env"
        ]));
    }

    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) continue;
        if (!str_contains($line, '=')) continue;

        [$key, $val] = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($val);
    }
}
