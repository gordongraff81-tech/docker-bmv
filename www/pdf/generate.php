<?php
/**
 * GET /pdf/generate.php?year=YYYY&kw=NN[&type=kantine]
 *
 * Erzeugt einen Wochenspeiseplan als PDF und sendet ihn direkt an den Browser.
 * Nutzt Python (reportlab) für pixelgenaues Layout.
 *
 * Voraussetzungen auf dem Server:
 *   - Python 3 + pip install reportlab
 *   - /pdf/speiseplan_generator.py  (liegt neben dieser Datei)
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/api/_bootstrap.php';

$current = current_iso_week();

$year = isset($_GET['year']) && ctype_digit((string)$_GET['year'])
      ? (int)$_GET['year'] : $current['year'];
$kw   = isset($_GET['kw'])   && ctype_digit((string)$_GET['kw'])
      ? (int)$_GET['kw']   : $current['kw'];
$type = in_array($_GET['type'] ?? '', ['kantine'], true) ? 'kantine' : 'ear';

if ($year < 2020 || $year > 2050) { http_response_code(400); echo 'Ungültiges Jahr.'; exit; }
if ($kw   < 1    || $kw   > 53)   { http_response_code(400); echo 'Ungültige KW.';   exit; }

$json_file = DATA_DIR . '/speiseplaene/' . $year . '-KW' . sprintf('%02d', $kw) . '.json';

if (!file_exists($json_file)) {
    http_response_code(404);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => "Kein Speiseplan für KW {$kw}/{$year}."]);
    exit;
}

$out_file  = tempnam(sys_get_temp_dir(), 'bmv_sp_') . '.pdf';
$py_script = __DIR__ . '/speiseplan_generator.py';

$cmd = sprintf(
    'python3 %s --json %s --year %d --kw %d --type %s --out %s 2>&1',
    escapeshellarg($py_script),
    escapeshellarg($json_file),
    $year, $kw,
    escapeshellarg($type),
    escapeshellarg($out_file)
);

exec($cmd, $output, $exit_code);

if ($exit_code !== 0 || !file_exists($out_file) || filesize($out_file) < 100) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'PDF-Generierung fehlgeschlagen.',
                      'detail'  => implode("\n", $output)]);
    exit;
}

$filename = 'BMV_Speiseplan_KW' . $kw . '_' . $year . '.pdf';
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="' . $filename . '"');
header('Content-Length: ' . filesize($out_file));
header('Cache-Control: no-store');
readfile($out_file);
unlink($out_file);
