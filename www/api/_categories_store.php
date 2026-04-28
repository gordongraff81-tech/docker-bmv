<?php
/**
 * _categories_store.php
 * Persistenz-Helper für Kategorien (ohne Request-Handling).
 */
require_once __DIR__ . '/_json_store.php';

function categories_default_payload(): array
{
    return [
        'version'    => 1,
        'updatedAt'  => bmv_now_iso(),
        'categories' => [],
    ];
}

function categories_load(): array
{
    $payload = bmv_store_read('categories', categories_default_payload());
    if (!isset($payload['categories']) || !is_array($payload['categories'])) {
        $payload = categories_default_payload();
    }
    return $payload;
}

function categories_save(array $payload): void
{
    $payload['version']   = 1;
    $payload['updatedAt'] = bmv_now_iso();
    bmv_store_write('categories', $payload);
}

function categories_seed_from_menu_database_v2_if_missing(): void
{
    $path = bmv_store_path('categories');
    if (file_exists($path)) return;

    $src = $_SERVER['DOCUMENT_ROOT'] . '/admin/menu_database_v2.json';
    if (!file_exists($src)) {
        categories_save(categories_default_payload());
        return;
    }

    $raw = json_decode(file_get_contents($src), true);
    if (!$raw || !is_array($raw) || empty($raw['systems']) || !is_array($raw['systems'])) {
        categories_save(categories_default_payload());
        return;
    }

    $categories = [];
    foreach ($raw['systems'] as $systemKey => $sys) {
        if (empty($sys['categories']) || !is_array($sys['categories'])) continue;
        foreach ($sys['categories'] as $catKey => $cat) {
            $label = trim((string)($cat['label'] ?? $catKey));
            $id    = bmv_uuid();
            $now   = bmv_now_iso();
            $categories[] = [
                'id'        => $id,
                'system'    => (string)$systemKey,
                'key'       => (string)$catKey,
                'label'     => $label ?: (string)$catKey,
                'createdAt' => $now,
                'updatedAt' => $now,
            ];
        }
    }

    categories_save([
        'version'    => 1,
        'updatedAt'  => bmv_now_iso(),
        'categories' => $categories,
        'seededFrom' => 'admin/menu_database_v2.json',
    ]);
}

