<?php
/**
 * includes/helpers.php – Gemeinsame Hilfsfunktionen für BMV-Menüdienst
 *
 * Einbinden mit: require_once __DIR__ . '/../includes/helpers.php';
 */

// ── Tage einer ISO-Kalenderwoche ──────────────────────────────────────────────
/**
 * Gibt ein Array mit 7 DateTimeImmutable-Objekten zurück (Mo–So der gegebenen KW).
 */
function kwDates(int $year, int $kw): array {
    $kw   = max(1, min(53, $kw));
    $year = max(2000, min(2099, $year));
    
    // Sicherere Initialisierung via setISODate
    $base = (new DateTimeImmutable())->setISODate($year, $kw, 1)->setTime(0, 0, 0);
    
    $days = [];
    for ($i = 0; $i < 7; $i++) {
        $days[] = $base->modify("+$i days");
    }
    return $days;
}

// ── Vorherige / nächste Kalenderwoche ─────────────────────────────────────────
/**
 * Berechnet year/KW nach Addition von $delta Wochen.
 *
 * @return array{0: int, 1: int}  [$year, $kw]
 */
function addKW(int $year, int $kw, int $delta): array {
    $kw   = max(1, min(53, $kw));
    $year = max(2000, min(2099, $year));

    $dt = (new DateTimeImmutable())->setISODate($year, $kw, 1)->setTime(0, 0, 0);
    $dt = $dt->modify("$delta weeks");

    return [(int)$dt->format('o'), (int)$dt->format('W')];
}

// ── Maximale KW korrekt ermitteln ─────────────────────────────────────────────
/**
 * Gibt die maximale gültige KW-Nummer für ein gegebenes Jahr zurück (52 oder 53).
 */
function maxKWOfYear(int $year): int {
    return (int)(new DateTimeImmutable("$year-12-28"))->format('W');
}

// ── Grenzen für die Navigation berechnen ─────────────────────────────────────
function kwNavBounds(int $year, int $kw, int $currentYear, int $currentKW, int $maxWeeksAhead = 4): array {
    [$prevYear, $prevKW] = addKW($year, $kw, -1);
    [$nextYear, $nextKW] = addKW($year, $kw, +1);

    [$maxYear, $maxKW] = addKW($currentYear, $currentKW, $maxWeeksAhead);

    $isAtMin = $prevYear < $currentYear || ($prevYear === $currentYear && $prevKW < $currentKW);
    $isAtMax = $nextYear > $maxYear     || ($nextYear === $maxYear     && $nextKW > $maxKW);

    return compact('isAtMin', 'isAtMax', 'prevYear', 'prevKW', 'nextYear', 'nextKW');
}

// ── Deutsche Feiertage (Brandenburg) ─────────────────────────────────────────
function getFeiertage(int $year): array {
    $a = $year % 19;
    $b = intdiv($year, 100);
    $c = $year % 100;
    $d = intdiv($b, 4);
    $e = $b % 4;
    $f = intdiv($b + 8, 25);
    $g = intdiv($b - $f + 1, 3);
    $h = (19 * $a + $b - $d - $g + 15) % 30;
    $i = intdiv($c, 4);
    $k = $c % 4;
    $l = (32 + 2 * $e + 2 * $i - $h - $k) % 7;
    $m = intdiv($a + 11 * $h + 22 * $l, 451);
    $monat = intdiv($h + $l - 7 * $m + 114, 31);
    $tag   = (($h + $l - 7 * $m + 114) % 31) + 1;

    $ostern = (new DateTimeImmutable())->setDate($year, $monat, $tag)->setTime(0, 0, 0);

    $feiertage = [];
    foreach ([
        '01-01' => 'Neujahr',
        '05-01' => 'Tag der Arbeit',
        '10-03' => 'Tag der Deutschen Einheit',
        '10-31' => 'Reformationstag',
        '12-25' => '1. Weihnachtstag',
        '12-26' => '2. Weihnachtstag',
    ] as $md => $name) {
        $feiertage["$year-$md"] = $name;
    }

    foreach ([
        -2  => 'Karfreitag',
        0   => 'Ostersonntag',
        1   => 'Ostermontag',
        39  => 'Christi Himmelfahrt',
        49  => 'Pfingstsonntag',
        50  => 'Pfingstmontag',
    ] as $offset => $name) {
        $dt = $ostern->modify("$offset days");
        $feiertage[$dt->format('Y-m-d')] = $name;
    }

    return $feiertage;
}

// ── Menü-Eintrag aus Tag-Array holen ─────────────────────────────────────────
function getMenu(?array $day, int $n): ?array {
    if (!$day) return null;
    foreach ($day['menus'] ?? [] as $m) {
        if ((int)$m['menu_number'] === $n) return $m;
    }
    return null;
}

// ── Addon-Eintrag aus Tag-Array holen ────────────────────────────────────────
function getAddon(?array $day, string $code): ?array {
    if (!$day) return null;
    foreach ($day['addons'] ?? [] as $a) {
        if ($a['code'] === $code) return $a;
    }
    return null;
}

// ── Deutsches Gericht → englischer Pexels-Suchbegriff ────────────────────────
function dishSearchQuery(string $name): string {
    static $map = [
        'klopse'          => 'meatballs white sauce',
        'königsberger'    => 'meatballs capers',
        'roulade'         => 'beef roulade german',
        'gulasch'         => 'goulash stew',
        'schnitzel'       => 'schnitzel breaded',
        'braten'          => 'roast meat',
        'kassler'         => 'smoked pork chop',
        'hähnchen'        => 'roasted chicken',
        'frikassee'       => 'chicken fricassee',
        'frikadelle'      => 'meat patty',
        'leberkäse'       => 'meatloaf german',
        'currywurst'      => 'currywurst fries',
        'bauernfrühstück' => 'german omelette potato',
        'geschnetzeltes'  => 'meat strips cream sauce',
        'hack'            => 'ground meat dish',
        'fisch'           => 'fish fillet plate',
        'lachs'           => 'salmon grilled',
        'zander'          => 'white fish fillet',
        'forelle'         => 'trout fish',
        'backfisch'       => 'fried fish',
        'scholle'         => 'plaice fish fillet',
        'nudel'           => 'pasta dish',
        'spätzle'         => 'spaetzle pasta',
        'kartoffel'       => 'potato side dish',
        'püree'           => 'mashed potatoes',
        'klöße'           => 'dumplings',
        'knödel'          => 'dumplings',
        'reis'            => 'rice bowl',
        'gemüse'          => 'mixed vegetables',
        'eintopf'         => 'hearty stew',
        'suppe'           => 'soup bowl',
        'salat'           => 'fresh salad',
        'reispfanne'      => 'rice skillet vegetable',
        'eierkuchen'      => 'pancakes',
        'pfannkuchen'     => 'pancakes',
        'milchreis'       => 'rice pudding',
        'grieß'           => 'semolina pudding',
        'pudding'         => 'custard dessert',
        'quark'           => 'curd cheese fruit',
        'kompott'         => 'fruit compote',
        'kuchen'          => 'cake slice',
        'obst'            => 'fruit salad bowl',
    ];

    $lower = mb_strtolower($name);
    foreach ($map as $key => $val) {
        if (str_contains($lower, $key)) return $val;
    }

    // KORREKTUR: Einfache Backslashes im Regex
    $cleanName = trim(preg_replace('/\s*\(.*?\)\s*/', ' ', $name));
    return $cleanName . ' food dish';
}

// ── Speiseplan-JSON sicher laden ──────────────────────────────────────────────
function loadJsonFile(string $path, int $maxBytes = 524288): ?array {
    if (!file_exists($path)) return null;
    if (filesize($path) > $maxBytes) {
        error_log("BMV helpers.php: JSON-Datei zu groß: $path");
        return null;
    }
    $raw = file_get_contents($path);
    if ($raw === false) return null;
    try {
        return json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
    } catch (\JsonException $e) { // KORREKTUR: Globaler Namespace \
        error_log("BMV helpers.php: JSON-Fehler in $path — " . $e->getMessage());
        return null;
    }
}

// ── API-Response mit Caching laden ───────────────────────────────────────────
function fetchApiWithCache(string $url, string $cacheFile, int $ttl = 900): ?array {
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $ttl) {
        return loadJsonFile($cacheFile);
    }
    $ctx = stream_context_create(['http' => ['timeout' => 3, 'ignore_errors' => false]]);
    $raw = @file_get_contents($url, false, $ctx);
    if ($raw === false) {
        return file_exists($cacheFile) ? loadJsonFile($cacheFile) : null;
    }
    file_put_contents($cacheFile, $raw, LOCK_EX);
    try {
        return json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
    } catch (\JsonException $e) { // KORREKTUR: Globaler Namespace \
        return null;
    }
}

// ── Bild-HTML standardisiert ausgeben ───────────────────────────────────────
function bmv_img(string $src, string $alt, int $width, int $height, bool $above_fold = false, string $class = ''): string {
    $attributes = [
        'src' => htmlspecialchars($src, ENT_QUOTES, 'UTF-8'),
        'alt' => htmlspecialchars($alt, ENT_QUOTES, 'UTF-8'),
        'width' => (string)$width,
        'height' => (string)$height,
        'loading' => $above_fold ? 'eager' : 'lazy',
        'decoding' => 'async',
    ];
    if ($above_fold) $attributes['fetchpriority'] = 'high';
    if ($class !== '') $attributes['class'] = htmlspecialchars($class, ENT_QUOTES, 'UTF-8');

    $parts = [];
    foreach ($attributes as $name => $value) {
        $parts[] = sprintf('%s="%s"', $name, $value);
    }
    return '<img ' . implode(' ', $parts) . '>';
}