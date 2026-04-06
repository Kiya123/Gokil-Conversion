<?php

/**
 * curl_client.php — cURL Helper Functions
 *
 * Dua fungsi reusable untuk HTTP request via cURL:
 *   - curlGet()  → dipakai download_proxy.php
 *   - curlPost() → dipakai upload_handler.php
 *
 * Return format (keduanya sama):
 *   ['body' => string, 'status' => int, 'error' => string]
 */

/**
 * Kirim HTTP GET request via cURL.
 *
 * @param string   $url     URL target
 * @param string[] $headers Header HTTP tambahan (format: ['Key: Value'])
 * @param int      $timeout Timeout dalam detik
 * @return array{body: string|false, status: int, error: string}
 */
function curlGet(string $url, array $headers = [], int $timeout = 30): array
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => $timeout,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTPHEADER     => $headers,
    ]);

    $body   = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error  = curl_error($ch);

    return ['body' => $body, 'status' => $status, 'error' => $error];
}

/**
 * Kirim HTTP POST request via cURL (multipart/form-data).
 *
 * @param string   $url      URL target
 * @param array    $postData Data yang akan di-POST (bisa berisi CURLFile)
 * @param string[] $headers  Header HTTP tambahan
 * @param int      $timeout  Timeout dalam detik
 * @return array{body: string|false, status: int, error: string}
 */
function curlPost(string $url, array $postData, array $headers = [], int $timeout = 30): array
{
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => $url,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $postData,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => $timeout,
        CURLOPT_HTTPHEADER     => $headers,
    ]);

    $body   = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error  = curl_error($ch);

    return ['body' => $body, 'status' => $status, 'error' => $error];
}
