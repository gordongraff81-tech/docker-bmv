<?php
/**
 * /api/categories.php
 * GET  -> Kategorien abrufen
 * POST -> Kategorie erstellen (Admin)
 */
require_once __DIR__ . '/_json_store.php';
require_once __DIR__ . '/_categories_store.php';

categories_seed_from_menu_database_v2_if_missing();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $payload = categories_load();
    api_json([
        'success'    => true,
        'categories' => $payload['categories'],
        'updatedAt'  => $payload['updatedAt'] ?? null,
    ]);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    api_error('Nur GET/POST erlaubt.', 405);
}

validate_admin_key();
$body = read_json_body();

$label  = sanitize((string)($body['label'] ?? ''), 80);
$system = sanitize((string)($body['system'] ?? ''), 30);
$key    = sanitize((string)($body['key'] ?? ''), 40);

if ($label === '') api_error('Kategorie-Name ist erforderlich.', 422);
if ($system === '' || !in_array($system, ['essen_auf_raedern', 'kantine'], true)) {
    api_error('Ungültiges System.', 422);
}
if ($key === '') $key = bmv_slug($label);
if ($key === '') api_error('Kategorie-Key ist ungültig.', 422);

$payload = categories_load();
$existing = array_values(array_filter($payload['categories'], function ($c) use ($system, $key) {
    return (($c['system'] ?? '') === $system) && (($c['key'] ?? '') === $key);
}));
if (!empty($existing)) {
    api_error('Kategorie existiert bereits.', 409);
}

$now = bmv_now_iso();
$cat = [
    'id'        => bmv_uuid(),
    'system'    => $system,
    'key'       => $key,
    'label'     => $label,
    'createdAt' => $now,
    'updatedAt' => $now,
];

$payload['categories'][] = $cat;
categories_save($payload);

api_json(['success' => true, 'category' => $cat], 201);

