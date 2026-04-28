<?php
/**
 * /api/dishes.php
 *
 * GET:
 *   - liefert Dish-Liste + Kategorien (für Admin-UI & DB-Modal)
 *
 * POST (Admin):
 *   { action: "create"|"update"|"delete", ... }
 */
require_once __DIR__ . '/_json_store.php';
require_once __DIR__ . '/_categories_store.php';

function dishes_default_payload(): array
{
    return [
        'version'   => 1,
        'updatedAt' => bmv_now_iso(),
        'dishes'    => [],
    ];
}

function dishes_load(): array
{
    $payload = bmv_store_read('dishes', dishes_default_payload());
    if (!isset($payload['dishes']) || !is_array($payload['dishes'])) {
        $payload = dishes_default_payload();
    }
    return $payload;
}

function dishes_save(array $payload): void
{
    $payload['version']   = 1;
    $payload['updatedAt'] = bmv_now_iso();
    bmv_store_write('dishes', $payload);
}

function dishes_seed_from_menu_database_v2_if_missing(): void
{
    $path = bmv_store_path('dishes');
    if (file_exists($path)) return;

    $src = $_SERVER['DOCUMENT_ROOT'] . '/admin/menu_database_v2.json';
    if (!file_exists($src)) {
        dishes_save(dishes_default_payload());
        return;
    }

    $raw = json_decode(file_get_contents($src), true);
    if (!$raw || !is_array($raw) || empty($raw['systems']) || !is_array($raw['systems'])) {
        dishes_save(dishes_default_payload());
        return;
    }

    // Preise sind im v2-File nicht enthalten → Default je Kategorie.
    $priceDefaults = [
        'essen_auf_raedern' => [
            'vollkost'     => 7.50,
            'leichte_kost' => 7.20,
            'premium'      => 9.80,
            'tagesmenu'    => 6.50,
            'dessert'      => 1.80,
            'rohkost'      => 1.80,
            'abendessen'   => 5.50,
            'salat'        => 5.50,
        ],
        'kantine' => [
            'kantine_menu1' => 0.01,
            'kantine_menu2' => 0.01,
            'kantine_menu3' => 0.01,
        ],
    ];

    // Kategorien-ID-Map aus categories store (seeded dort ebenfalls).
    categories_seed_from_menu_database_v2_if_missing();
    $catPayload = categories_load();
    $catIdBySystemKey = [];
    foreach ($catPayload['categories'] as $c) {
        $sys = (string)($c['system'] ?? '');
        $key = (string)($c['key'] ?? '');
        $id  = (string)($c['id'] ?? '');
        if ($sys && $key && $id) $catIdBySystemKey[$sys . '::' . $key] = $id;
    }

    $now = bmv_now_iso();
    $dishes = [];
    foreach ($raw['systems'] as $systemKey => $sys) {
        if (empty($sys['categories']) || !is_array($sys['categories'])) continue;
        foreach ($sys['categories'] as $catKey => $cat) {
            $catId = $catIdBySystemKey[$systemKey . '::' . $catKey] ?? null;
            if (!$catId) continue;
            $items = $cat['items'] ?? [];
            if (!is_array($items)) continue;
            foreach ($items as $item) {
                $name = trim((string)($item['name'] ?? ''));
                if ($name === '') continue;
                $alg  = trim((string)($item['allergens'] ?? ''));
                $allergens = [];
                if ($alg !== '') {
                    foreach (preg_split('/\s*,\s*/', $alg) as $a) {
                        $a = trim($a);
                        if ($a !== '') $allergens[] = $a;
                    }
                    $allergens = array_values(array_unique($allergens));
                }
                $price = (float)($priceDefaults[$systemKey][$catKey] ?? 0.01);
                $dishes[] = [
                    'id'        => bmv_uuid(),
                    'name'      => $name,
                    'price'     => round(max($price, 0.01), 2),
                    'category'  => $catId,
                    'allergens' => $allergens,
                    'createdAt' => $now,
                    'updatedAt' => $now,
                ];
            }
        }
    }

    dishes_save([
        'version'    => 1,
        'updatedAt'  => bmv_now_iso(),
        'dishes'     => $dishes,
        'seededFrom' => 'admin/menu_database_v2.json',
    ]);
}

function validate_dish_input(array $in, array $categoryIds): array
{
    $errors = [];

    $name = sanitize((string)($in['name'] ?? ''), 120);
    if ($name === '') $errors['name'] = 'Name ist erforderlich.';

    $priceRaw = $in['price'] ?? null;
    $price = is_numeric($priceRaw) ? (float)$priceRaw : 0.0;
    if ($price <= 0) $errors['price'] = 'Preis muss größer als 0 sein.';

    $category = sanitize((string)($in['category'] ?? ''), 80);
    if ($category === '' || !in_array($category, $categoryIds, true)) {
        $errors['category'] = 'Kategorie ist erforderlich.';
    }

    $allergensIn = $in['allergens'] ?? [];
    $allergens = [];
    if (is_string($allergensIn)) {
        // Fallback: falls aus älterem UI als String kommt → split.
        $allergensIn = preg_split('/\s*,\s*/', $allergensIn);
    }
    if (is_array($allergensIn)) {
        foreach ($allergensIn as $a) {
            $a = sanitize((string)$a, 40);
            if ($a !== '') $allergens[] = $a;
        }
    } else {
        $errors['allergens'] = 'Allergene müssen ein Array sein.';
    }
    $allergens = array_values(array_unique($allergens));

    if (!empty($errors)) {
        api_json(['success' => false, 'message' => 'Validierungsfehler.', 'errors' => $errors], 422);
    }

    return [
        'name'      => $name,
        'price'     => round($price, 2),
        'category'  => $category,
        'allergens' => $allergens,
    ];
}

dishes_seed_from_menu_database_v2_if_missing();

// Immer Kategorien mitliefern (UI braucht sie).
categories_seed_from_menu_database_v2_if_missing();
$catPayload = categories_load();
$categories = $catPayload['categories'];
$categoryIds = array_values(array_map(fn($c) => (string)($c['id'] ?? ''), $categories));

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $payload = dishes_load();

    // Optional filter
    $q = isset($_GET['q']) ? mb_strtolower(trim((string)$_GET['q'])) : '';
    $system = isset($_GET['system']) ? sanitize((string)$_GET['system'], 30) : '';
    $categoryId = isset($_GET['category']) ? sanitize((string)$_GET['category'], 80) : '';

    $catById = [];
    foreach ($categories as $c) {
        if (!empty($c['id'])) $catById[(string)$c['id']] = $c;
    }

    $dishes = $payload['dishes'];
    if ($system || $categoryId || $q) {
        $dishes = array_values(array_filter($dishes, function ($d) use ($q, $system, $categoryId, $catById) {
            $catId = (string)($d['category'] ?? '');
            $cat   = $catById[$catId] ?? null;
            if ($system && (!$cat || ($cat['system'] ?? '') !== $system)) return false;
            if ($categoryId && $catId !== $categoryId) return false;
            if ($q) {
                $name = mb_strtolower((string)($d['name'] ?? ''));
                if (!str_contains($name, $q)) return false;
            }
            return true;
        }));
    }

    api_json([
        'success'    => true,
        'dishes'     => $dishes,
        'categories' => $categories,
        'updatedAt'  => $payload['updatedAt'] ?? null,
    ]);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    api_error('Nur GET/POST erlaubt.', 405);
}

validate_admin_key();
$body   = read_json_body();
$action = sanitize((string)($body['action'] ?? ''), 20);

if (!in_array($action, ['create', 'update', 'delete'], true)) {
    api_error('Ungültige Aktion.', 422);
}

$payload = dishes_load();

if ($action === 'create') {
    $clean = validate_dish_input($body, $categoryIds);
    $now = bmv_now_iso();
    $dish = [
        'id'        => bmv_uuid(),
        'name'      => $clean['name'],
        'price'     => $clean['price'],
        'category'  => $clean['category'],
        'allergens' => $clean['allergens'],
        'createdAt' => $now,
        'updatedAt' => $now,
    ];
    array_unshift($payload['dishes'], $dish);
    dishes_save($payload);
    api_json(['success' => true, 'dish' => $dish], 201);
}

if ($action === 'update') {
    $id = sanitize((string)($body['id'] ?? ''), 60);
    if ($id === '') api_error('id ist erforderlich.', 422);
    $clean = validate_dish_input($body, $categoryIds);

    $found = false;
    foreach ($payload['dishes'] as &$d) {
        if (($d['id'] ?? '') === $id) {
            $d['name']      = $clean['name'];
            $d['price']     = $clean['price'];
            $d['category']  = $clean['category'];
            $d['allergens'] = $clean['allergens'];
            $d['updatedAt'] = bmv_now_iso();
            $found = true;
            $dish = $d;
            break;
        }
    }
    unset($d);
    if (!$found) api_error('Gericht nicht gefunden.', 404);

    dishes_save($payload);
    api_json(['success' => true, 'dish' => $dish]);
}

// delete
$id = sanitize((string)($body['id'] ?? ''), 60);
if ($id === '') api_error('id ist erforderlich.', 422);
$before = count($payload['dishes']);
$payload['dishes'] = array_values(array_filter($payload['dishes'], fn($d) => (($d['id'] ?? '') !== $id)));
if (count($payload['dishes']) === $before) api_error('Gericht nicht gefunden.', 404);
dishes_save($payload);
api_json(['success' => true]);

