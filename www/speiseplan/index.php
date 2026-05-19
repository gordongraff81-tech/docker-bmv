<?php
/**
 * speiseplan/index.php – Wochenspeiseplan
 * Nutzt helpers.php für alle Hilfsfunktionen (keine Duplikate)
 */
require_once __DIR__ . '/../includes/helpers.php';

$currentYear = (int)date('Y');
$currentKW   = (int)date('W');
$year = isset($_GET['year']) ? (int)$_GET['year'] : $currentYear;
$kw   = isset($_GET['kw'])   ? (int)$_GET['kw']   : $currentKW;

// Navigations-Grenzen (aus helpers.php)
[$prevYear, $prevKW] = addKW($year, $kw, -1);
[$nextYear, $nextKW] = addKW($year, $kw, +1);
$bounds   = kwNavBounds($year, $kw, $currentYear, $currentKW, 4);
$isAtMin  = $bounds['isAtMin'];
$isAtMax  = $bounds['isAtMax'];

$days      = kwDates($year, $kw);
$dayNames  = ['Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag','Sonntag'];
$dayShort  = ['Mo','Di','Mi','Do','Fr','Sa','So'];
$feiertage = getFeiertage($year);

// ── Daten laden ──────────────────────────────────────────────────
$kwStr   = str_pad($kw, 2, '0', STR_PAD_LEFT);
// Versuche zuerst das neue Format: essen_YYYY_KWxx.json
$newFile = __DIR__ . "/../data/speiseplaene/essen_{$year}_KW{$kwStr}.json";
// Fallback zum Admin-Format: essen_auf_raedern-YYYY-KWxx.json
$adminFile = __DIR__ . "/../data/speiseplaene/essen_auf_raedern-{$year}-KW{$kwStr}.json";

$plan = null;
if (file_exists($newFile)) {
    $plan = json_decode(file_get_contents($newFile), true);
} elseif (file_exists($adminFile)) {
    $adminData = json_decode(file_get_contents($adminFile), true);
    // Konvertiere Admin-Format zu Speiseplan-Format
    $plan = convertAdminFormatToMenu($adminData, $year, $kw);
}

// Map für schnellen Zugriff
$dayMap = [];
if ($plan && !empty($plan['days'])) {
    foreach ($plan['days'] as $d) {
        $dt = new DateTimeImmutable($d['date']);
        $dow = (int)$dt->format('N') - 1;
        $dayMap[$dow] = $d;
    }
}
$prices = $plan['prices'] ?? [1=>6.20, 2=>6.40, 3=>7.20, 4=>6.20];

// ── Konvertiere Admin-Format zu Menu-Format ──────────────────────────────────────
function convertAdminFormatToMenu($adminData, $year, $kw) {
    if (!$adminData || !is_array($adminData)) return null;
    
    $plan = [
        'year' => $year,
        'kw' => $kw,
        'updated_at' => date('Y-m-d'),
        'prices' => [1 => 6.20, 2 => 6.40, 3 => 7.20, 4 => 6.20],
        'days' => []
    ];
    
    // Admin-Format: { "Monday": { "M1": { "name": "...", "price": 7.5, ... }, ... }, ... }
    $dayOrder = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    $dateStart = (new DateTimeImmutable())->setISODate($year, $kw, 1);
    
    foreach ($dayOrder as $idx => $dayName) {
        if ($idx > 4) break; // Nur Mo-Fr
        
        $dayData = $adminData[$dayName] ?? null;
        if (!$dayData) continue;
        
        $date = $dateStart->modify("+$idx days");
        $menus = [];
        
        // M1, M2, M3, M4 Menüs zusammenfassen
        for ($n = 1; $n <= 4; $n++) {
            $menuKey = "M$n";
            $item = $dayData[$menuKey] ?? null;
            if ($item && !empty($item['name'])) {
                $menus[] = [
                    'menu_number' => $n,
                    'label' => "Menü $n",
                    'title' => $item['name'],
                    'description' => $item['name'],
                    'price' => (float)($item['price'] ?? $plan['prices'][$n]),
                    'allergens' => $item['allergens'] ?? null,
                    'available' => true
                ];
            }
        }
        
        if (!empty($menus)) {
            $plan['days'][] = [
                'date' => $date->format('Y-m-d'),
                'menus' => $menus,
                'addons' => [
                    ['code' => 'SUPPE', 'name' => 'Tagessuppe', 'price' => 1.80],
                    ['code' => 'NACHT', 'name' => 'Nachtisch', 'price' => 1.20]
                ]
            ];
        }
    }
    
    return !empty($plan['days']) ? $plan : null;
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Speiseplan KW <?= $kw ?>/<?= $year ?></title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="site-header screen-only">
  <div class="logo">BMV Menüdienst</div>
  <button class="print-btn" onclick="window.print()">Drucken</button>
</header>

<nav class="week-nav screen-only">
  <a href="?year=<?= $prevYear ?>&kw=<?= $prevKW ?>" class="nav-btn <?= $isAtMin ? 'disabled' : '' ?>">← Zurück</a>
  <div class="week-info">
    <div class="kw-label">KW <?= $kw ?> / <?= $year ?></div>
    <div class="date-range">
      <?= $days[0]->format('d.m.Y') ?> – <?= $days[4]->format('d.m.Y') ?>
    </div>
  </div>
  <a href="?year=<?= $nextYear ?>&kw=<?= $nextKW ?>" class="nav-btn <?= $isAtMax ? 'disabled' : '' ?>">Weiter →</a>
</nav>

<main class="main screen-only">
  <div class="days-grid">
    <?php foreach ($days as $i => $d): 
      if ($i > 4) continue; // Nur Mo-Fr
      $active = isset($dayMap[$i]);
      // KORREKTUR: Objekt-Zugriff via format()
      $holiday = $feiertage[$d->format('Y-m-d')] ?? null; 
    ?>
    <div class="day-card <?= !$active ? 'empty' : '' ?>">
      <div class="day-header <?= $holiday ? 'holiday' : '' ?>">
        <span class="day-name"><?= $dayNames[$i] ?></span>
        <span class="day-date"><?= $d->format('d.m.Y') ?></span>
        <?php if ($holiday): ?><span class="h-badge"><?= $holiday ?></span><?php endif; ?>
      </div>

      <div class="menu-list">
        <?php for ($n=1; $n<=4; $n++): 
          $m = getMenu($dayMap[$i] ?? null, $n);
          $title = $m['title'] ?? "Menü $n nicht verfügbar";
          $price = $m['price'] ?? $prices[$n];
        ?>
        <div class="menu-item" onclick='openPopup(<?= json_encode(["num"=>$n,"title"=>$title,"price"=>number_format($price,2,",",".")." €","alg"=>$m["allergens"]??null]) ?>)'>
          <div class="menu-badge">M<?= $n ?></div>
          <div class="menu-title"><?= htmlspecialchars($title) ?></div>
          <div class="menu-price"><?= number_format($price, 2, ',', '.') ?> €</div>
        </div>
        <?php endfor; ?>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</main>

<div id="popup" class="popup-overlay" onclick="if(event.target===this)closePopup()">
  <div class="popup-box">
    <div id="popup-num"></div>
    <h2 id="popup-title"></h2>
    <div id="popup-price"></div>
    <p id="popup-alg"></p>
    <button onclick="closePopup()">Schließen</button>
  </div>
</div>

<script>
function openPopup(data) {
  document.getElementById('popup-num').textContent = 'Menü ' + data.num;
  document.getElementById('popup-title').textContent = data.title;
  document.getElementById('popup-price').textContent = data.price;
  document.getElementById('popup-alg').textContent = data.alg ? 'Allergene: ' + data.alg : 'Keine Allergene';
  document.getElementById('popup').classList.add('open');
}
function closePopup() { document.getElementById('popup').classList.remove('open'); }
</script>

</body>
</html>
