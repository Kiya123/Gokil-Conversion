<?php

/**
 * image_processor.php — GD Library Helpers
 *
 * Fungsi-fungsi untuk load dan convert gambar via GD Library.
 * Dipisah dari upload.php agar logic GD bisa ditest dan
 * dipakai ulang tanpa bergantung ke HTTP context.
 */

/**
 * Load gambar dari path temporary ke GdImage resource.
 * Otomatis handle alpha channel untuk PNG dan WebP.
 *
 * @param string $tmpPath  Path file temporary hasil upload
 * @param string $mimeType MIME type gambar (dari mime_content_type)
 * @return \GdImage|false  GdImage object, atau false jika gagal
 */
function loadSourceImage(string $tmpPath, string $mimeType): \GdImage|false
{
    $image = match($mimeType) {
        'image/jpeg' => imagecreatefromjpeg($tmpPath),
        'image/png'  => imagecreatefrompng($tmpPath),
        'image/webp' => imagecreatefromwebp($tmpPath),
        default      => false,
    };

    if ($image === false) return false;

    // Preserve alpha channel untuk format yang mendukungnya
    if (in_array($mimeType, ['image/png', 'image/webp'])) {
        imagealphablending($image, false);
        imagesavealpha($image, true);
    }

    return $image;
}

/**
 * Simpan GdImage ke file dengan format target.
 *
 * @param \GdImage $image      GdImage source
 * @param string   $outputPath Path output file
 * @param string   $format     Format target: 'jpg', 'png', atau 'webp'
 * @return bool    true jika berhasil disimpan
 */
function saveConvertedImage(\GdImage $image, string $outputPath, string $format): bool
{
    return match($format) {
        'jpg'  => imagejpeg($image, $outputPath, 85),   // quality 85 — balance size & kualitas
        'png'  => imagepng($image, $outputPath, 6),     // compression level 6 (0–9)
        'webp' => imagewebp($image, $outputPath, 80),   // quality 80
        default => false,
    };
}
