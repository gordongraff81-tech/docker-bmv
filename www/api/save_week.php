<?php
/**
 * POST /api/save_week.php
 * Content-Type: application/json
 *
 * Admin-Endpunkt: Speiseplan für eine Woche speichern.
 * Nur aus dem lokalen Netz oder mit Admin-Passwort nutzbar.
 *
 * Request-Body: gleiche Struktur wie get_week.php Response
 * {
 *   "year": 2025,
 *   "kw":   12,
 *   "admin_key": "GEHEIMER_SCHLÜSSEL",   // Pflicht
 *   "days": [ ... ]
 * }
 *
 * Response:
 * { "success": true, "message": "KW 12/2025 gespeichert." }
 */

require_once __DIR__ . '/_bootstrap.php';
require_post();

/* ── Admin-Authentifizierung ───────────────────────────────── */
// Admin-Key aus Umgebungsvariable oder Fallback
// Bitte in .env oder Apache/Nginx-Config setzen:
//   SetEnv BMV_ADMIN_KEY "langer-zufaelliger-schluessel"
$expected_key = getenv('BMV_ADMIN_KEY') ?: 'BITTE_IN_.ENV_SETZEN';

$body      = read_json_body();
$admin_key = sanitize($body['admin_key'] ?? '');

if (empty($admin_key) || !hash_equals($expected_key, $admin_key)) {
    // Rate-Limiting verhindern durch kurze Verzögerung
    sleep(1);
    api_error('Nicht autorisiert.', 401);
}

/* ── Parameter ─────────────────────────────────────────────── */
$year = isset($body['year']) ? (int)$body['year'] : 0;
$kw   = isset($body['kw'])   ? (int)$body['kw']   : 0;
$days = $body['days'] ?? null;

if ($year < 2020 || $year > 2050) api_error('Ungültiges Jahr.');
if ($kw   < 1    || $kw   > 53)   api_error('Ungültige KW.');
if (!is_array($days))              api_error('Pflichtfeld "days" fehlt.');

/* ── Tage validieren & normalisieren ───────────────────────── */
$clean_days = [];
$menu_labels = [1 => 'Vollkost', 2 => 'Leichte Kost', 3 => 'Premium', 4 => 'Tagesmenü'];

foreach ($days as $day) {
    if (!isset($day['date']) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $day['date'])) continue;

    $clean_menus = [];
    foreach ($day['menus'] ?? [] as $menu) {
        $n = (int)($menu['menu_number'] ?? 1);
        if ($n < 1 || $n > 9) continue;
        if (empty($menu['title']))       continue; // Titel Pflicht

        $clean_menus[] = [
            'menu_number' => $n,
            'label'       => sanitize($menu['label'] ?? ($menu_labels[$n] ?? 'Menü ' . $n)),
            'title'       => sanitize($menu['title'], 120),
            'description' => sanitize($menu['description'] ?? '', 300),
            'price'       => round((float)($menu['price'] ?? 7.50), 2),
            'allergens'   => sanitize($menu['allergens'] ?? '', 80),
            'available'   => (bool)($menu['available'] ?? true),
        ];
    }

    $clean_addons = [];
    foreach ($day['addons'] ?? [] as $addon) {
        if (empty($addon['code'])) continue;
        $clean_addons[] = [
            'code'  => sanitize($addon['code'], 20),
            'name'  => sanitize($addon['name'] ?? '', 60),
            'price' => round((float)($addon['price'] ?? 0), 2),
        ];
    }

    $clean_days[] = [
        'date'   => $day['date'],
        'menus'  => $clean_menus,
        'addons' => $clean_addons,
    ];
}

/* ── Speichern ─────────────────────────────────────────────── */
$speiseplan = [
    'year'       => $year,
    'kw'         => $kw,
    'updated_at' => date('Y-m-d H:i:s'),
    'days'       => $clean_days,
];

if (!save_speiseplan($year, $kw, $speiseplan)) {
    api_error('Fehler beim Speichern. Bitte Schreibrechte auf /data/speiseplaene/ prüfen.', 500);
}

api_json([
    'success' => true,
    'message' => "KW {$kw}/{$year} mit " . count($clean_days) . " Tag(en) gespeichert.",
    'year'    => $year,
    'kw'      => $kw,
    'days'    => count($clean_days),
]);
