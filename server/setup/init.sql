-- =============================================
--  init.sql — Gokil Conversion Database Setup
--  Jalankan sekali di phpMyAdmin atau via MySQL CLI
-- =============================================

-- Buat database kalau belum ada
CREATE DATABASE IF NOT EXISTS gokil_conversion
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE gokil_conversion;

-- Tabel log setiap konversi yang berhasil
CREATE TABLE IF NOT EXISTS conversion_logs (
    id                  INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    original_filename   VARCHAR(255)    NOT NULL COMMENT 'Nama file asli dari client',
    converted_filename  VARCHAR(255)    NOT NULL COMMENT 'Nama file hasil konversi',
    original_format     VARCHAR(10)     NOT NULL COMMENT 'Format input (jpg/png/webp)',
    target_format       VARCHAR(10)     NOT NULL COMMENT 'Format output yang diminta',
    original_size       INT UNSIGNED    NOT NULL COMMENT 'Ukuran file asli dalam bytes',
    converted_size      INT UNSIGNED    NOT NULL COMMENT 'Ukuran file hasil dalam bytes',
    client_ip           VARCHAR(45)     NOT NULL COMMENT 'IP address laptop client (Dier)',
    download_url        TEXT            NOT NULL COMMENT 'URL untuk download hasil konversi',
    created_at          TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    INDEX idx_created_at (created_at),
    INDEX idx_original_format (original_format),
    INDEX idx_target_format (target_format)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
