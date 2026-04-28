<?php
/**
 * _json_store.php
 * Kleine JSON-"DB" Helpers (atomic writes, validation friendly).
 */
require_once __DIR__ . '/_bootstrap.php';

function bmv_store_path(string $name): string
{
    $name = preg_replace('/[^a-z0-9_\-]/i', '', $name);
    if (!$name) api_error('Ungültiger Store-Name.', 500);
    if (!is_dir(DATA_DIR)) mkdir(DATA_DIR, 0750, true);
    return DATA_DIR . '/' . $name . '.json';
}

function bmv_store_read(string $name, array $default): array
{
    $path = bmv_store_path($name);
    if (!file_exists($path)) return $default;
    $raw = file_get_contents($path);
    if ($raw === false || $raw === '') return $default;
    $data = json_decode($raw, true);
    if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) return $default;
    return $data;
}

function bmv_store_write(string $name, array $data): void
{
    $path = bmv_store_path($name);
    $tmp  = $path . '.tmp';
    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    if ($json === false) api_error('JSON Encoding fehlgeschlagen.', 500);

    if (file_put_contents($tmp, $json, LOCK_EX) === false) {
        api_error('Konnte nicht speichern.', 500);
    }
    if (!@rename($tmp, $path)) {
        @unlink($tmp);
        api_error('Konnte nicht finalisieren.', 500);
    }
}

function bmv_uuid(): string
{
    $bytes = random_bytes(16);
    $bytes[6] = chr((ord($bytes[6]) & 0x0f) | 0x40);
    $bytes[8] = chr((ord($bytes[8]) & 0x3f) | 0x80);
    $hex = bin2hex($bytes);
    return sprintf('%s-%s-%s-%s-%s',
        substr($hex, 0, 8),
        substr($hex, 8, 4),
        substr($hex, 12, 4),
        substr($hex, 16, 4),
        substr($hex, 20, 12)
    );
}

function bmv_now_iso(): string
{
    return date('c');
}

function bmv_slug(string $label): string
{
    $label = mb_strtolower(trim($label));
    $label = preg_replace('/[^\p{L}\p{N}]+/u', '_', $label);
    $label = preg_replace('/_+/', '_', $label);
    $label = trim($label, '_');
    return substr($label, 0, 40);
}

