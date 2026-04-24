<?php
require_once __DIR__ . '/location-pages.php';

function bmv_sitemap_base_url(): string
{
    return 'https://www.bmv-kantinen.de';
}

function bmv_sitemap_webroot(): string
{
    return dirname(__DIR__);
}

function bmv_sitemap_project_root(): string
{
    return dirname(bmv_sitemap_webroot());
}

function bmv_sitemap_entry(string $path, array $sourceFiles, array $images = [], array $extraGlobs = []): array
{
    $timestamps = [];

    foreach ($sourceFiles as $sourceFile) {
        if (is_file($sourceFile)) {
            $timestamps[] = filemtime($sourceFile);
        }
    }

    foreach ($extraGlobs as $globPattern) {
        $matched = glob($globPattern) ?: [];
        foreach ($matched as $matchedFile) {
            if (is_file($matchedFile)) {
                $timestamps[] = filemtime($matchedFile);
            }
        }
    }

    $images = array_values(array_filter(array_map(
        static function (string $relativeImagePath): ?string {
            $relativeImagePath = '/' . ltrim($relativeImagePath, '/');
            $absoluteImagePath = bmv_sitemap_webroot() . str_replace('/', DIRECTORY_SEPARATOR, $relativeImagePath);
            return is_file($absoluteImagePath) ? bmv_sitemap_base_url() . $relativeImagePath : null;
        },
        $images
    )));

    return [
        'path' => $path,
        'loc' => bmv_sitemap_base_url() . $path,
        'lastmod_ts' => !empty($timestamps) ? max($timestamps) : null,
        'images' => $images,
    ];
}

function bmv_sitemap_location_entries(): array
{
    $entries = [];
    $locationPages = bmv_location_pages();
    $webroot = bmv_sitemap_webroot();
    $template = __DIR__ . '/location-template.php';
    $config = __DIR__ . '/location-pages.php';

    foreach ($locationPages as $slug => $locationPage) {
        $pageFile = $webroot . str_replace('/', DIRECTORY_SEPARATOR, $locationPage['canonical_path']) . 'index.php';
        $entries[] = bmv_sitemap_entry(
            $locationPage['canonical_path'],
            [$pageFile, $template, $config],
            [$locationPage['image']]
        );
    }

    return $entries;
}

function bmv_sitemap_groups(): array
{
    $webroot = bmv_sitemap_webroot();
    $projectRoot = bmv_sitemap_project_root();

    $pages = [
        bmv_sitemap_entry('/', [$webroot . '/index.php'], [
            '/assets/images/essen-auf-raedern-lieferung.jpg',
            '/assets/images/og-image.jpg',
        ]),
        bmv_sitemap_entry('/speiseplan/', [$webroot . '/speiseplan/index.php'], [
            '/assets/images/speiseplan-kueche.jpg',
            '/assets/images/placeholder-meal.jpg',
        ], [
            $projectRoot . '/data/speiseplaene/*.json',
            $webroot . '/data/speiseplaene/*.json',
        ]),
        bmv_sitemap_entry('/kontakt/', [$webroot . '/kontakt/index.php'], [
            '/assets/images/og-image.jpg',
        ]),
        bmv_sitemap_entry('/ueber-uns/', [$webroot . '/ueber-uns/index.php'], [
            '/assets/images/ueber-uns-team.jpg',
            '/assets/images/og-image.jpg',
        ]),
        bmv_sitemap_entry('/sitemap/', [$webroot . '/sitemap/index.php']),
    ];

    $services = [
        bmv_sitemap_entry('/essen-auf-raedern/', [$webroot . '/essen-auf-raedern/index.php'], [
            '/assets/images/essen-auf-raedern-lieferung.jpg',
            '/assets/images/potsdam-lieferung.jpg',
        ]),
        bmv_sitemap_entry('/catering/', [$webroot . '/catering/index.php'], [
            '/assets/images/catering-setup.jpg',
            '/assets/images/og-image.jpg',
        ]),
        bmv_sitemap_entry('/kantine-am-gutshof/', [$webroot . '/kantine-am-gutshof/index.php'], [
            '/assets/images/kantine-gutshof.jpg',
            '/assets/images/speiseplan-kueche.jpg',
        ]),
    ];

    $locations = bmv_sitemap_location_entries();
    $imageEntries = array_values(array_filter(
        array_merge($pages, $services, $locations),
        static fn(array $entry): bool => !empty($entry['images'])
    ));

    return [
        'pages' => $pages,
        'services' => $services,
        'locations' => $locations,
        'image' => $imageEntries,
        'blog' => [],
    ];
}

function bmv_sitemap_group_lastmod(array $entries): ?int
{
    $timestamps = array_values(array_filter(array_column($entries, 'lastmod_ts'), 'is_int'));

    if (empty($timestamps)) {
        return null;
    }

    return max($timestamps);
}
