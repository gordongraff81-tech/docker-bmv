<?php
/**
 * /api/pexels_image.php  v3
 * Nutzt AIQueryService für intelligente Dish→Query-Konvertierung.
 * Statisches Mapping zuerst, OpenAI als Fallback (gecacht).
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/AIQueryService.php';

// ── API-Keys & Setup ──────────────────────────────────────────────────────────
$pexelsKey = getenv('PEXELS_API_KEY');
if (!$pexelsKey) {
    echo json_encode(['url' => null, 'error' => 'PEXELS_API_KEY not configured']);
    exit;
}

$query = trim($_GET['q'] ?? '');
if ($query === '') {
    echo json_encode(['url' => null]);
    exit;
}
$query = mb_substr($query, 0, 200, 'UTF-8');

// ── Pexels-Cache ──────────────────────────────────────────────────────────────
$pexelsCacheDir = __DIR__ . '/../data/img_cache/';
if (!is_dir($pexelsCacheDir)) {
    @mkdir($pexelsCacheDir, 0755, true);
}
$pexelsCacheFile = $pexelsCacheDir . md5(mb_strtolower($query, 'UTF-8')) . '.json';
$pexelsCacheTTL  = 7 * 24 * 3600;

if (file_exists($pexelsCacheFile) && (time() - filemtime($pexelsCacheFile)) < $pexelsCacheTTL) {
    readfile($pexelsCacheFile);
    exit;
}

// ── Query-Optimierung via AIQueryService ──────────────────────────────────────
$aiService   = new AIQueryService();
$searchQuery = $aiService->getQuery($query);

// ── Pexels API ────────────────────────────────────────────────────────────────
$url = 'https://api.pexels.com/v1/search?' . http_build_query([
    'query'       => $searchQuery,
    'per_page'    => 8,
    'orientation' => 'landscape',
    'size'        => 'medium',
    'locale'      => 'en-US',
]);

$ctx = stream_context_create([
    'http' => [
        'method'        => 'GET',
        'header'        => implode("\r\n", [
            'Authorization: ' . $pexelsKey,
            'User-Agent: BMV-Menuservice/1.0',
            'Accept: application/json',
        ]),
        'timeout'       => 6,
        'ignore_errors' => true,
    ],
]);

$raw    = @file_get_contents($url, false, $ctx);
$result = [
    'url'         => null,
    'query_used'  => $searchQuery,
    'dish_input'  => $query,
];

if ($raw !== false) {
    $data = json_decode($raw, true);
    if (!empty($data['photos'])) {
        $count = min(count($data['photos']), 8);
        // Deterministisch: gleiche Query → immer gleiche Foto-Auswahl
        $idx   = abs(crc32(mb_strtolower($query, 'UTF-8'))) % $count;
        $photo = $data['photos'][$idx];

        $result['url']         = $photo['src']['medium']       ?? null;
        $result['url_small']   = $photo['src']['small']        ?? null;
        $result['credit']      = $photo['photographer']        ?? '';
        $result['credit_url']  = $photo['photographer_url']    ?? '';
    }
}

$json = json_encode($result, JSON_UNESCAPED_UNICODE);
@file_put_contents($pexelsCacheFile, $json, LOCK_EX);
echo $json;
