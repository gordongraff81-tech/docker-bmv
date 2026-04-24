<?php
require_once __DIR__ . '/includes/sitemap-data.php';

$scope = $_GET['sitemap'] ?? 'sitemap';
$scope = strtolower((string)$scope);
$groups = bmv_sitemap_groups();
$validScopes = ['sitemap', 'sitemap_index', 'pages', 'services', 'locations', 'image', 'blog'];

if (!in_array($scope, $validScopes, true)) {
    http_response_code(404);
    header('Content-Type: text/plain; charset=UTF-8');
    echo 'Unknown sitemap scope.';
    exit;
}

header('Content-Type: application/xml; charset=UTF-8');

function bmv_xml_escape(string $value): string
{
    return htmlspecialchars($value, ENT_XML1 | ENT_COMPAT, 'UTF-8');
}

function bmv_xml_lastmod(int $timestamp): string
{
    return gmdate('Y-m-d\TH:i:s\Z', $timestamp);
}

if ($scope === 'sitemap' || $scope === 'sitemap_index') {
    $sitemapFiles = [
        'pages' => 'pages-sitemap.xml',
        'locations' => 'locations-sitemap.xml',
        'image' => 'image-sitemap.xml',
        'blog' => 'blog-sitemap.xml',
        'services' => 'services-sitemap.xml',
    ];

    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    echo "<sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
    foreach ($sitemapFiles as $groupName => $fileName) {
        $groupLastmod = bmv_sitemap_group_lastmod($groups[$groupName]);
        echo "  <sitemap>\n";
        echo '    <loc>' . bmv_xml_escape(bmv_sitemap_base_url() . '/' . $fileName) . "</loc>\n";
        if (is_int($groupLastmod)) {
            echo '    <lastmod>' . bmv_xml_lastmod($groupLastmod) . "</lastmod>\n";
        }
        echo "  </sitemap>\n";
    }
    echo "</sitemapindex>\n";
    exit;
}

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";

if ($scope === 'image') {
    echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\" xmlns:image=\"http://www.google.com/schemas/sitemap-image/1.1\">\n";
    foreach ($groups['image'] as $entry) {
        echo "  <url>\n";
        echo '    <loc>' . bmv_xml_escape($entry['loc']) . "</loc>\n";
        if (is_int($entry['lastmod_ts'])) {
            echo '    <lastmod>' . bmv_xml_lastmod($entry['lastmod_ts']) . "</lastmod>\n";
        }
        foreach ($entry['images'] as $imageUrl) {
            echo "    <image:image>\n";
            echo '      <image:loc>' . bmv_xml_escape($imageUrl) . "</image:loc>\n";
            echo "    </image:image>\n";
        }
        echo "  </url>\n";
    }
    echo "</urlset>\n";
    exit;
}

echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
foreach ($groups[$scope] as $entry) {
    echo "  <url>\n";
    echo '    <loc>' . bmv_xml_escape($entry['loc']) . "</loc>\n";
    if (is_int($entry['lastmod_ts'])) {
        echo '    <lastmod>' . bmv_xml_lastmod($entry['lastmod_ts']) . "</lastmod>\n";
    }
    echo "  </url>\n";
}
echo "</urlset>\n";
