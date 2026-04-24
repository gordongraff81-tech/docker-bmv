<?php
/**
 * Lightweight vanity-slug router.
 * Only redirects known marketing aliases and otherwise does nothing.
 */

$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

if (!is_string($requestPath)) {
    return;
}

$normalizedPath = rtrim($requestPath, '/') . '/';

$slugMap = [
    '/potsdam/' => '/essen-auf-raedern/potsdam/',
    '/werder-havel/' => '/essen-auf-raedern/werder-havel/',
    '/umland/' => '/essen-auf-raedern/umland/',
];

if (isset($slugMap[$normalizedPath])) {
    header('Location: ' . $slugMap[$normalizedPath], true, 301);
    exit;
}
