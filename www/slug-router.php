<?php
/**
 * www/slug-router.php
 *
 * Handles "vanity" URL slugs that do not correspond to physical directories.
 * Nginx routes all 404-directory requests to /index.php?$query_string.
 * $_SERVER['REQUEST_URI'] carries the original path.
 *
 * Strategy: map known slugs to their canonical physical URL via redirect.
 * This keeps every subpage self-contained and avoids a monolithic index.php router.
 *
 * Add new slugs here as the site grows.
 */

$slug_map = [
    // Essen auf Rädern – regional landing pages
    'werder-havel'           => '/essen-auf-raedern/werder-havel/',
    'essen-werder'           => '/essen-auf-raedern/werder-havel/',
    'essen-auf-raedern-werder' => '/essen-auf-raedern/werder-havel/',

    'potsdam'                => '/essen-auf-raedern/potsdam/',
    'essen-potsdam'          => '/essen-auf-raedern/potsdam/',
    'essen-auf-raedern-potsdam' => '/essen-auf-raedern/potsdam/',

    'umland'                 => '/essen-auf-raedern/umland/',
    'essen-umland'           => '/essen-auf-raedern/umland/',

    // Catering – regional landing pages
    'catering-potsdam'       => '/catering/potsdam/',
    'catering-werder'        => '/catering/werder-havel/',
    'catering-werder-havel'  => '/catering/werder-havel/',

    // Convenience aliases
    'speiseplan-potsdam'     => '/speiseplan/',
    'kantinenspeiseplan'     => '/speiseplan/',
    'kantine'                => '/kantine-am-gutshof/',
    'mittagstisch'           => '/kantine-am-gutshof/',
    'mittagessen'            => '/essen-auf-raedern/',
    'lieferdienst'           => '/essen-auf-raedern/',
    'senioren'               => '/essen-auf-raedern/',
];

// Only activate when Nginx has passed a slug via query string
// OR when the URI does not match a physical file/directory.
$uri_path = strtok($_SERVER['REQUEST_URI'] ?? '', '?');
$uri_path = trim($uri_path, '/');

if (!empty($uri_path) && isset($slug_map[$uri_path])) {
    $target = $slug_map[$uri_path];
    header('Location: ' . $target, true, 301);
    exit;
}

// Also handle the legacy ?slug= parameter pattern (for Apache compatibility
// or future use) without breaking current Nginx-based routing.
if (!empty($_GET['slug']) && isset($slug_map[$_GET['slug']])) {
    $target = $slug_map[$_GET['slug']];
    header('Location: ' . $target, true, 301);
    exit;
}
