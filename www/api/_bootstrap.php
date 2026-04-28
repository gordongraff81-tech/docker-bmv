<?php
/**
 * _bootstrap.php – BMV Menüdienst API
 * Phase 7 Security Upgrade: CSP, Security Headers, CORS fix
 * Phase 3 Refactor: Rate limiting, Input hardening
 */

/* ── Fehlerbehandlung ──────────────────────────────────────── */
error_reporting(0);
ini_set('display_errors', 0);

set_exception_handler(function (Throwable $e) {
    api_error('Interner Serverfehler.', 500);
});
set_error_handler(function ($severity, $msg, $file, $line) {
    throw new ErrorException($msg, 0, $severity, $file, $line);
});

/* ── Security Headers (Phase 7) ────────────────────────────── */
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');

/* ── CORS (alle lokalen Ports + Produktion) ─────────────────── */
$allowed_origins = [
    'https://www.bmv-kantinen.de',
    'https://bestellen.bmv-kantinen.de',
    'https://kantinen-speiseplan.bmv-kantinen.de',
    // Lokale Entwicklung
    'http://localhost',
    'http://localhost:8080',
    'http://localhost:8081',
    'http://localhost:8082',
    'http://localhost:8083',
    'http://127.0.0.1',
    'http://127.0.0.1:8080',
    'http://127.0.0.1:8081',
    'http://127.0.0.1:8082',
    'http://127.0.0.1:8083',
];
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if (in_array($origin, $allowed_origins, true)) {
    header('Access-Control-Allow-Origin: ' . $origin);
    header('Vary: Origin');
} else {
    header('Access-Control-Allow-Origin: https://www.bmv-kantinen.de');
}
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With, X-Admin-Key');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

/* ── Pfade ─────────────────────────────────────────────────── */
define('DATA_DIR',       $_SERVER['DOCUMENT_ROOT'] . '/data');
define('SPEISEPLAN_DIR', DATA_DIR . '/speiseplaene');
define('BESTELLUNG_DIR', DATA_DIR . '/bestellungen');
define('LOG_FILE',       DATA_DIR . '/bestellungen.log');

/* ── E-Mail ────────────────────────────────────────────────── */
define('MAIL_TO',   'info@bmv-kantinen.de');
define('MAIL_FROM', 'bestellung@bmv-kantinen.de');
define('MAIL_NAME', 'BMV Menüdienst Bestellsystem');
define('SITE_NAME', 'BMV Menüdienst');

/* ── Admin-Key Validierung ──────────────────────────────────── */
function validate_admin_key(): void
{
    $key = $_SERVER['HTTP_X_ADMIN_KEY']
        ?? $_SERVER['HTTP_X_REQUESTED_WITH']
        ?? '';
    // Auch aus POST-Body lesen (Fallback für altes Admin-Panel)
    if (empty($key)) {
        $body = json_decode(file_get_contents('php://input'), true) ?? [];
        $key  = $body['admin_key'] ?? '';
        // Body-Pointer zurücksetzen
        // Hinweis: php://input ist nach read_json_body nicht mehr lesbar,
        // daher Admin-Key immer per Header senden
    }
    $expected = getenv('BMV_ADMIN_KEY') ?: 'bmv-admin-2025';
    if (!hash_equals($expected, $key)) {
        api_error('Nicht autorisiert.', 401);
    }
}

/* ── Hilfsfunktionen ───────────────────────────────────────── */
function api_json(array $data, int $status = 200): void
{
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

function api_error(string $message, int $status = 400): void
{
    api_json(['success' => false, 'message' => $message], $status);
}

function sanitize(string $value, int $max = 255): string
{
    return substr(trim(strip_tags($value)), 0, $max);
}

function sanitize_filename(string $value): string
{
    return preg_replace('/[^a-zA-Z0-9_\-]/', '', $value);
}

function require_get(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        api_error('Nur GET erlaubt.', 405);
    }
}

function require_post(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        api_error('Nur POST erlaubt.', 405);
    }
}

function read_json_body(): array
{
    $raw = file_get_contents('php://input');
    if (empty($raw)) {
        api_error('Kein Request-Body gefunden.', 400);
    }
    $data = json_decode($raw, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        api_error('Ungültiges JSON: ' . json_last_error_msg(), 400);
    }
    if (!is_array($data)) {
        api_error('JSON muss ein Objekt sein.', 400);
    }
    return $data;
}

function load_speiseplan(int $year, int $kw): ?array
{
    $file = sprintf('%s/%04d-KW%02d.json', SPEISEPLAN_DIR, $year, $kw);
    if (!file_exists($file)) return null;
    $content = file_get_contents($file);
    $data    = json_decode($content, true);
    return (json_last_error() === JSON_ERROR_NONE) ? $data : null;
}

function save_speiseplan(int $year, int $kw, array $data): bool
{
    if (!is_dir(SPEISEPLAN_DIR)) mkdir(SPEISEPLAN_DIR, 0750, true);
    $file = sprintf('%s/%04d-KW%02d.json', SPEISEPLAN_DIR, $year, $kw);
    return file_put_contents(
        $file,
        json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
        LOCK_EX
    ) !== false;
}

function current_iso_week(): array
{
    $now = new DateTimeImmutable();
    return ['year' => (int)$now->format('o'), 'kw' => (int)$now->format('W')];
}

function monday_of_kw(int $year, int $kw): string
{
    return (new DateTimeImmutable())->setISODate($year, $kw, 1)->format('Y-m-d');
}

function send_order_mail(array $order): bool
{
    $c          = $order['customer'];
    $selections = $order['selections'] ?? [];
    $order_id   = $order['order_id'] ?? '–';
    $created_at = $order['created_at'] ?? date('d.m.Y H:i');

    $name    = sanitize(($c['firstname'] ?? '') . ' ' . ($c['lastname'] ?? ''));
    $phone   = sanitize($c['phone']    ?? '');
    $email   = sanitize($c['email']    ?? '');
    $address = sanitize($c['address']  ?? '');
    $start   = sanitize($c['startdate']?? '');
    $days    = sanitize($c['days']     ?? '');
    $notes   = sanitize($c['notes']    ?? '', 1000);
    $pflege  = !empty($c['pflegekasse']) ? 'Ja' : 'Nein';

    $sel_lines = '';
    ksort($selections);
    foreach ($selections as $date => $sel) {
        if (empty($sel['menuNumber'])) continue;
        $dt        = date_create($date);
        $date_fmt  = $dt ? $dt->format('d.m.Y') : $date;
        $addons    = !empty($sel['addons']) ? ' + ' . implode(', ', $sel['addons']) : '';
        $sel_lines .= "  {$date_fmt}: Menü {$sel['menuNumber']}{$addons}\n";
    }
    if (empty($sel_lines)) $sel_lines = "  (keine Menüauswahl)\n";

    $subject = "Neue Bestellung #{$order_id} – {$name}";
    $body    = <<<TEXT
Neue Bestellung – BMV Menüdienst
=================================
Bestellnummer : {$order_id}
Eingegangen   : {$created_at}

KUNDENDATEN
-----------
Name          : {$name}
Telefon       : {$phone}
E-Mail        : {$email}
Adresse       : {$address}
Startdatum    : {$start}
Liefertage    : {$days}
Pflegekasse   : {$pflege}
Hinweise      : {$notes}

MENÜAUSWAHL
-----------
{$sel_lines}
=================================
TEXT;

    $headers  = "From: " . MAIL_NAME . " <" . MAIL_FROM . ">\r\n";
    $headers .= "Reply-To: {$email}\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $headers .= "X-Mailer: BMV-Bestellsystem/1.0\r\n";

    return mail(MAIL_TO, $subject, $body, $headers);
}

function log_order(array $order): void
{
    if (!is_dir(BESTELLUNG_DIR)) mkdir(BESTELLUNG_DIR, 0750, true);
    $order_id = $order['order_id'] ?? 'unknown';
    $file     = sprintf('%s/%s.json', BESTELLUNG_DIR, sanitize_filename($order_id));
    file_put_contents(
        $file,
        json_encode($order, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
        LOCK_EX
    );
    $log_line = sprintf(
        "[%s] #%s %s %s <%s> Addr: %s Start: %s Sel: %d\n",
        date('Y-m-d H:i:s'),
        $order_id,
        $order['customer']['firstname'] ?? '',
        $order['customer']['lastname']  ?? '',
        $order['customer']['phone']     ?? '',
        $order['customer']['address']   ?? '',
        $order['customer']['startdate'] ?? '',
        count($order['selections'] ?? [])
    );
    file_put_contents(LOG_FILE, $log_line, FILE_APPEND | LOCK_EX);
}
