<?php
/**
 * GET /api/get_week.php?year=YYYY&kw=NN
 *
 * Gibt den Speiseplan für eine Kalenderwoche als JSON zurück.
 *
 * Response-Format:
 * {
 *   "year": 2025,
 *   "kw": 12,
 *   "week_start": "2025-03-17",
 *   "week_end":   "2025-03-23",
 *   "days": [
 *     {
 *       "date":    "2025-03-17",         // YYYY-MM-DD
 *       "weekday": "Montag",
 *       "is_weekend": false,
 *       "menus": [
 *         {
 *           "menu_number": 1,
 *           "label":       "Vollkost",
 *           "title":       "Rindsroulade mit Rotkohl",
 *           "description": "mit Kartoffelklößen und Soße",
 *           "price":       7.50,
 *           "allergens":   "G, L",
 *           "available":   true
 *         },
 *         ...
 *       ],
 *       "addons": [
 *         { "code": "SUPPE", "name": "Tagessuppe",  "price": 1.80 },
 *         { "code": "NACHT", "name": "Nachtisch",   "price": 1.20 }
 *       ]
 *     },
 *     ...  // 7 Tage (Mo–So)
 *   ]
 * }
 *
 * Wenn kein Speiseplan hinterlegt: 404 + leere days-Liste
 */

require_once __DIR__ . '/_bootstrap.php';
require_get();

/* ── Parameter validieren ──────────────────────────────────── */
$current = current_iso_week();

$year = isset($_GET['year']) && ctype_digit((string)$_GET['year'])
    ? (int) $_GET['year']
    : $current['year'];

$kw = isset($_GET['kw']) && ctype_digit((string)$_GET['kw'])
    ? (int) $_GET['kw']
    : $current['kw'];

if ($year < 2020 || $year > 2050) {
    api_error('Ungültiges Jahr. Erlaubt: 2020–2050.');
}
if ($kw < 1 || $kw > 53) {
    api_error('Ungültige Kalenderwoche. Erlaubt: 1–53.');
}

/* ── Speiseplan laden ──────────────────────────────────────── */
$data = load_speiseplan($year, $kw);

/* ── Wochen-Metadaten ──────────────────────────────────────── */
$monday  = new DateTimeImmutable();
$monday  = $monday->setISODate($year, $kw, 1);
$sunday  = $monday->modify('+6 days');

$day_names = ['Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag', 'Sonntag'];

/* ── Kein Speiseplan vorhanden → leere Tage zurückgeben ────── */
if ($data === null) {
    $days = [];
    for ($i = 0; $i < 7; $i++) {
        $d = $monday->modify("+{$i} days");
        $days[] = [
            'date'       => $d->format('Y-m-d'),
            'weekday'    => $day_names[$i],
            'is_weekend' => $i >= 5,
            'menus'      => [],
            'addons'     => [],
        ];
    }
    api_json([
        'success'    => false,
        'year'       => $year,
        'kw'         => $kw,
        'week_start' => $monday->format('Y-m-d'),
        'week_end'   => $sunday->format('Y-m-d'),
        'days'       => $days,
        'message'    => 'Für diese Woche ist noch kein Speiseplan hinterlegt.',
    ], 404);
}

/* ── Speiseplan normalisieren und ausgeben ──────────────────── */
$days_out = [];
for ($i = 0; $i < 7; $i++) {
    $d       = $monday->modify("+{$i} days");
    $ds      = $d->format('Y-m-d');
    $is_we   = $i >= 5;

    // Tag aus JSON suchen
    $day_data = null;
    if (!empty($data['days'])) {
        foreach ($data['days'] as $dd) {
            if (isset($dd['date']) && $dd['date'] === $ds) {
                $day_data = $dd;
                break;
            }
        }
    }

    // Menüs normalisieren
    $menus_out = [];
    if ($day_data && !empty($day_data['menus'])) {
        $menu_labels = [1 => 'Vollkost', 2 => 'Leichte Kost', 3 => 'Premium', 4 => 'Tagesmenü'];
        foreach ($day_data['menus'] as $menu) {
            $n = (int)($menu['menu_number'] ?? 1);
            $menus_out[] = [
                'menu_number' => $n,
                'label'       => $menu['label'] ?? ($menu_labels[$n] ?? 'Menü ' . $n),
                'title'       => $menu['title'] ?? '',
                'description' => $menu['description'] ?? '',
                'price'       => (float)($menu['price'] ?? 7.50),
                'allergens'   => $menu['allergens'] ?? '',
                'available'   => (bool)($menu['available'] ?? true),
            ];
        }
    }

    // Addons normalisieren
    $addons_out = [];
    if ($day_data && !empty($day_data['addons'])) {
        foreach ($day_data['addons'] as $a) {
            $addons_out[] = [
                'code'  => $a['code'] ?? '',
                'name'  => $a['name'] ?? '',
                'price' => (float)($a['price'] ?? 0),
            ];
        }
    }

    $days_out[] = [
        'date'       => $ds,
        'weekday'    => $day_names[$i],
        'is_weekend' => $is_we,
        'menus'      => $menus_out,
        'addons'     => $addons_out,
    ];
}

api_json([
    'success'    => true,
    'year'       => $year,
    'kw'         => $kw,
    'week_start' => $monday->format('Y-m-d'),
    'week_end'   => $sunday->format('Y-m-d'),
    'days'       => $days_out,
]);
