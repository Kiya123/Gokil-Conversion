<?php

/**
 * env_loader.php — Minimal .env Parser
 *
 * Parses plain KEY=VALUE format:
 *   - Lines starting with '#' are treated as comments
 *   - Empty lines are ignored
 *   - Values are trimmed of whitespace
 *
 * Usage: loadEnv(__DIR__ . '/../.env');
 */

function loadEnv(string $path): void
{
    if (!file_exists($path)) {
        // Tampilkan error yang ramah buat developer
        $hint = str_contains($path, 'client')
            ? "cp client/.env.example client/.env"
            : "cp server/.env.example server/.env";

        die(
            "<pre style='font-family:monospace;padding:20px;background:#0f0f1a;color:#ef4444;border-left:4px solid #ef4444;'>" .
            "⚠️  File .env tidak ditemukan!\n\n" .
            "Path: $path\n\n" .
            "Jalankan:\n  $hint\n\n" .
            "Lalu edit .env sesuai kebutuhan.\n" .
            "</pre>"
        );
    }

    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) continue;
        if (!str_contains($line, '=')) continue;

        [$key, $val] = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($val);
    }
}
