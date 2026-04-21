<?php
/**
 * /api/save_plan.php
 * Speichert Speiseplan-Daten für ein bestimmtes System (kantine / essen_auf_raedern)
 * POST JSON: { year, kw, system, published, data, week_start }
 */
require_once __DIR__ . '/_bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    api_error('Nur POST erlaubt.', 405);
}

// Admin-Key prüfen
$key = $_SERVER['HTTP_X_ADMIN_KEY'] ?? '';
if (!$key || $key !== (getenv('BMV_ADMIN_KEY') ?: 'bmv-admin-2025')) {
    api_error('Nicht autorisiert.', 401);
}

$body = json_decode(file_get_contents('php://input'), true);
if (!$body) api_error('Ungültiger JSON-Body.');

$year      = (int)($body['year']   ?? 0);
$kw        = (int)($body['kw']     ?? 0);
$system    = trim($body['system']  ?? '');
$published = (bool)($body['published'] ?? false);
$data      = $body['data']         ?? [];

// Validierung
if ($year < 2020 || $year > 2050) api_error('Ungültiges Jahr.');
if ($kw < 1 || $kw > 53)          api_error('Ungültige KW.');
if (!in_array($system, ['essen_auf_raedern', 'kantine'])) {
    api_error('Ungültiges System.');
}

// Dateiname
$kwStr    = str_pad($kw, 2, '0', STR_PAD_LEFT);
$filename = sprintf('%s/%s-%04d-KW%s.json', SPEISEPLAN_DIR, $system, $year, $kwStr);

// Sicherstellen dass Verzeichnis existiert
if (!is_dir(SPEISEPLAN_DIR)) {
    mkdir(SPEISEPLAN_DIR, 0755, true);
}

// Daten bereinigen
$cleanData = [];
foreach ($data as $dayIdx => $categories) {
    $cleanData[(int)$dayIdx] = [];
    foreach ($categories as $catKey => $entry) {
        $catKey = preg_replace('/[^a-z0-9_]/', '', $catKey);
        if (!$catKey) continue;
        $cleanData[(int)$dayIdx][$catKey] = [
            'name'      => mb_substr(strip_tags($entry['name']      ?? ''), 0, 200),
            'allergens' => mb_substr(strip_tags($entry['allergens'] ?? ''), 0, 100),
            'price'     => round((float)($entry['price'] ?? 0), 2),
        ];
    }
}

$payload = [
    'year'       => $year,
    'kw'         => $kw,
    'system'     => $system,
    'published'  => $published,
    'week_start' => $body['week_start'] ?? '',
    'saved_at'   => date('c'),
    'data'       => $cleanData,
];

if (file_put_contents($filename, json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)) === false) {
    api_error('Datei konnte nicht gespeichert werden.');
}

api_json([
    'success'  => true,
    'message'  => "KW {$kw}/{$year} ({$system}) gespeichert.",
    'file'     => basename($filename),
    'published'=> $published,
]);
