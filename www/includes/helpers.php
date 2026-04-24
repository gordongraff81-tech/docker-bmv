<?php
/**
 * includes/helpers.php – Gemeinsame Hilfsfunktionen für BMV-Menüdienst
 *
 * Einbinden mit: require_once __DIR__ . '/../includes/helpers.php';
 * (Pfad ggf. anpassen, je nach Dateitiefe)
 */

// ── Tage einer ISO-Kalenderwoche ──────────────────────────────────────────────
/**
 * Gibt ein Array mit 7 DateTimeImmutable-Objekten zurück (Mo–So der gegebenen KW).
 */
function kwDates(int $year, int $kw): array {
    $base = DateTimeImmutable::createFromFormat('o-W-N', "$year-$kw-1");
    $days = [];
    for ($i = 0; $i < 7; $i++) {
        $days[] = $base->modify("+$i days");
    }
    return $days;
}

// ── Vorherige / nächste Kalenderwoche ─────────────────────────────────────────
/**
 * Berechnet year/KW nach Addition von $delta Wochen.
 * Nutzt ISO-Jahresformat 'o' damit KW53 korrekt behandelt wird.
 *
 * @return array{0: int, 1: int}  [$year, $kw]
 */
function addKW(int $year, int $kw, int $delta): array {
    $sign = $delta >= 0 ? '+' : '';
    $dt   = DateTimeImmutable::createFromFormat('o-W-N', "$year-$kw-1")
                             ->modify("{$sign}{$delta} weeks");
    return [(int)$dt->format('o'), (int)$dt->format('W')];
}

// ── Maximale KW korrekt ermitteln (ISO: manche Jahre haben KW 53) ─────────────
/**
 * Gibt die maximale gültige KW-Nummer für ein gegebenes Jahr zurück (52 oder 53).
 */
function maxKWOfYear(int $year): int {
    // Der 28. Dezember liegt immer in der letzten KW des ISO-Jahres
    return (int)(new DateTimeImmutable("$year-12-28"))->format('W');
}

// ── Grenzen für die Navigation berechnen ─────────────────────────────────────
/**
 * Gibt zurück, ob Vor/Zurück-Navigation erlaubt ist.
 *
 * @return array{isAtMin: bool, isAtMax: bool}
 */
function kwNavBounds(int $year, int $kw, int $currentYear, int $currentKW, int $maxWeeksAhead = 4): array {
    [$prevYear, $prevKW] = addKW($year, $kw, -1);
    [$nextYear, $nextKW] = addKW($year, $kw, +1);

    // Max = aktuelle KW + $maxWeeksAhead (ISO-korrekt über Jahreswechsel)
    [$maxYear, $maxKW] = addKW($currentYear, $currentKW, $maxWeeksAhead);

    $isAtMin = $prevYear < $currentYear || ($prevYear === $currentYear && $prevKW < $currentKW);
    $isAtMax = $nextYear > $maxYear     || ($nextYear === $maxYear     && $nextKW > $maxKW);

    return compact('isAtMin', 'isAtMax', 'prevYear', 'prevKW', 'nextYear', 'nextKW');
}

// ── Deutsche Feiertage (Brandenburg) ─────────────────────────────────────────
/**
 * Gibt ein assoziatives Array zurück: ['Y-m-d' => 'Feiertagsname']
 * Enthält bewegliche Feiertage (Ostern-basiert) + Fixfeiertage für Brandenburg.
 */
function getFeiertage(int $year): array {
    // Gaußsche Osterformel
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

    $ostern = new DateTimeImmutable("$year-$monat-$tag");

    $feiertage = [];

    // Fixfeiertage
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

    // Bewegliche Feiertage (Offset in Tagen von Ostersonntag)
    foreach ([
        -2  => 'Karfreitag',
        0   => 'Ostersonntag',
        1   => 'Ostermontag',
        39  => 'Christi Himmelfahrt',
        49  => 'Pfingstsonntag',
        50  => 'Pfingstmontag',
    ] as $offset => $name) {
        $sign = $offset >= 0 ? '+' : '';
        $dt   = $ostern->modify("{$sign}{$offset} days");
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
/**
 * Vollständige Keyword-Map (aus speiseplan_index.php übernommen und bereinigt).
 */
function dishSearchQuery(string $name): string {
    static $map = [
        'suppe'       => 'soup bowl',
        'eintopf'     => 'hearty stew',
        'braten'      => 'roast meat german',
        'schnitzel'   => 'schnitzel breaded',
        'hähnchen'    => 'roasted chicken',
        'hühnchen'    => 'chicken dish',
        'frikassee'   => 'chicken fricassee',
        'hähnchenb'   => 'grilled chicken breast',
        'fisch'       => 'fish fillet plate',
        'lachs'       => 'salmon fillet',
        'forelle'     => 'trout fillet',
        'zander'      => 'white fish fillet',
        'hering'      => 'herring fish',
        'matjes'      => 'herring salad',
        'nudel'       => 'pasta dish',
        'spätzle'     => 'spaetzle german pasta',
        'spaghetti'   => 'spaghetti bolognese',
        'lasagne'     => 'lasagna baked',
        'nudeln'      => 'noodle dish',
        'kartoffel'   => 'potato dish',
        'püree'       => 'mashed potato',
        'klöße'       => 'potato dumplings',
        'knödel'      => 'dumplings german',
        'puffer'      => 'potato pancake',
        'gulasch'     => 'goulash stew',
        'roulade'     => 'beef roulade',
        'rind'        => 'beef dish',
        'schweine'    => 'pork roast',
        'pute'        => 'turkey roast',
        'leber'       => 'liver onions',
        'wurst'       => 'sausage plate',
        'bratwurst'   => 'bratwurst grilled',
        'kassler'     => 'smoked pork chop',
        'salat'       => 'salad fresh bowl',
        'rohkost'     => 'raw vegetables fresh',
        'gurke'       => 'cucumber salad',
        'tomate'      => 'tomato salad',
        'möhren'      => 'carrot dish',
        'rotkohl'     => 'red cabbage',
        'sauerkraut'  => 'sauerkraut dish',
        'gemüse'      => 'vegetables plate',
        'brokkoli'    => 'broccoli dish',
        'spinat'      => 'spinach dish',
        'pilz'        => 'mushroom cream sauce',
        'champignon'  => 'mushroom dish',
        'kuchen'      => 'cake slice',
        'torte'       => 'layer cake',
        'pudding'     => 'pudding dessert cream',
        'mousse'      => 'chocolate mousse',
        'eis'         => 'ice cream dessert',
        'grütze'      => 'berry compote',
        'milchreis'   => 'rice pudding',
        'grieß'       => 'semolina pudding',
        'obst'        => 'fresh fruit bowl',
        'apfel'       => 'apple dessert',
        'schokolade'  => 'chocolate dessert',
        'beere'       => 'berry dessert',
        'pfannkuchen' => 'pancakes stack',
        'eierkuchen'  => 'crepes french',
        'quark'       => 'curd cheese fresh',
        'ei'          => 'egg dish',
        'rührei'      => 'scrambled eggs',
        'käse'        => 'cheese plate',
        'reis'        => 'rice dish',
        'curry'       => 'curry dish',
        'couscous'    => 'couscous bowl',
        'paprika'     => 'stuffed bell pepper',
        'kohl'        => 'cabbage roll',
        'wirsing'     => 'stuffed cabbage',
        'toast'       => 'toast plate',
        'brot'        => 'bread plate',
        'aufschnitt'  => 'cold cuts deli',
    ];

    $lower = mb_strtolower($name);
    foreach ($map as $key => $val) {
        if (str_contains($lower, $key)) return $val;
    }

    // Fallback: Klammerzusätze entfernen, Rest als Suchbegriff
    return trim(preg_replace('/\s*\(.*?\)\s*/', ' ', $name)) . ' food dish';
}

// ── Speiseplan-JSON sicher laden ──────────────────────────────────────────────
/**
 * Lädt eine JSON-Datei mit Größenlimit und strikter Fehlerbehandlung.
 *
 * @param  string   $path      Absoluter Dateipfad
 * @param  int      $maxBytes  Maximale Dateigröße in Bytes (Standard: 512 KB)
 * @return array|null          Dekodiertes Array oder null bei Fehler
 */
function loadJsonFile(string $path, int $maxBytes = 524288): ?array {
    if (!file_exists($path)) return null;
    if (filesize($path) > $maxBytes) {
        error_log("BMV helpers.php: JSON-Datei zu groß (>$maxBytes Bytes): $path");
        return null;
    }
    $raw = file_get_contents($path);
    if ($raw === false) {
        error_log("BMV helpers.php: Konnte Datei nicht lesen: $path");
        return null;
    }
    try {
        return json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
    } catch (\JsonException $e) {
        error_log("BMV helpers.php: JSON-Fehler in $path — " . $e->getMessage());
        return null;
    }
}

// ── API-Response mit Caching laden ───────────────────────────────────────────
/**
 * Ruft eine interne API-URL auf, cached das Ergebnis als JSON-Datei.
 *
 * @param  string $url       Vollständige URL (z.B. http://localhost/api/...)
 * @param  string $cacheFile Absoluter Pfad zur Cache-Datei
 * @param  int    $ttl       Cache-Gültigkeitsdauer in Sekunden (Standard: 15 min)
 * @return array|null        Dekodiertes Array oder null
 */
function fetchApiWithCache(string $url, string $cacheFile, int $ttl = 900): ?array {
    // Cache frisch genug?
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $ttl) {
        return loadJsonFile($cacheFile);
    }

    $ctx = stream_context_create(['http' => [
        'timeout'        => 3,
        'ignore_errors'  => false,
    ]]);

    $raw = @file_get_contents($url, false, $ctx);

    if ($raw === false) {
        error_log("BMV helpers.php: API nicht erreichbar: $url");
        // Stale Cache als Fallback zurückgeben, falls vorhanden
        return file_exists($cacheFile) ? loadJsonFile($cacheFile) : null;
    }

    // Cache schreiben
    file_put_contents($cacheFile, $raw, LOCK_EX);

    try {
        return json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
    } catch (\JsonException $e) {
        error_log("BMV helpers.php: Ungültige API-Antwort von $url — " . $e->getMessage());
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

    if ($above_fold) {
        $attributes['fetchpriority'] = 'high';
    }

    if ($class !== '') {
        $attributes['class'] = htmlspecialchars($class, ENT_QUOTES, 'UTF-8');
    }

    $parts = [];
    foreach ($attributes as $name => $value) {
        $parts[] = sprintf('%s="%s"', $name, $value);
    }

    return '<img ' . implode(' ', $parts) . '>';
}
