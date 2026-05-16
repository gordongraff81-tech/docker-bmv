<?php
/**
 * POST /api/save_plan.php
 * Speichert Speiseplan-Daten für ein System (kantine / essen_auf_raedern).
 * Schreibt atomar via temp-file + rename, kein Hardcode-Fallback.
 */
require_once __DIR__ . '/_bootstrap.php';

require_post();
validate_admin_key();

$body = read_json_body();

$year      = (int)($body['year']      ?? 0);
$kw        = (int)($body['kw']        ?? 0);
$system    = trim($body['system']     ?? '');
$published = (bool)($body['published'] ?? false);
$data      = $body['data']            ?? [];

// Validierung
if ($year < 2020 || $year > 2050) api_error('Ungültiges Jahr.');
if ($kw < 1 || $kw > 53)          api_error('Ungültige KW.');
if (!in_array($system, ['essen_auf_raedern', 'kantine'], true)) {
    api_error('Ungültiges System.');
}

// Sicherstellen dass Verzeichnis existiert
if (!is_dir(SPEISEPLAN_DIR)) {
    mkdir(SPEISEPLAN_DIR, 0750, true);
}

// Dateiname: essen_auf_raedern-YYYY-KWNN.json
$kwStr    = str_pad($kw, 2, '0', STR_PAD_LEFT);
$filename = sprintf('%s/%s-%04d-KW%s.json', SPEISEPLAN_DIR, $system, $year, $kwStr);

// Daten bereinigen
$cleanData = [];
foreach ($data as $dayIdx => $categories) {
    if (!is_array($categories)) continue;
    $cleanData[(int)$dayIdx] = [];
    foreach ($categories as $catKey => $entry) {
        if (!is_array($entry)) continue;
        $catKey = preg_replace('/[^a-z0-9_]/', '', (string)$catKey);
        if (!$catKey) continue;
        $cleanData[(int)$dayIdx][$catKey] = [
            'name'      => mb_substr(strip_tags((string)($entry['name']      ?? '')), 0, 200),
            'allergens' => mb_substr(strip_tags((string)($entry['allergens'] ?? '')), 0, 100),
            'price'     => round((float)($entry['price'] ?? 0), 2),
        ];
    }
}

$payload = [
    'year'       => $year,
    'kw'         => $kw,
    'system'     => $system,
    'published'  => $published,
    'week_start' => preg_replace('/[^0-9\-]/', '', (string)($body['week_start'] ?? '')),
    'saved_at'   => date('c'),
    'data'       => $cleanData,
];

// Atomar schreiben: temp file + rename
$tmp = $filename . '.tmp.' . getmypid();
$written = file_put_contents(
    $tmp,
    json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
    LOCK_EX
);
if ($written === false) {
    api_error('Datei konnte nicht gespeichert werden.', 500);
}
if (!rename($tmp, $filename)) {
    @unlink($tmp);
    api_error('Datei konnte nicht finalisiert werden.', 500);
}

api_json([
    'success'   => true,
    'message'   => "KW {$kw}/{$year} ({$system}) gespeichert.",
    'file'      => basename($filename),
    'published' => $published,
]);
