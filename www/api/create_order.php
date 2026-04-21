<?php
/**
 * POST /api/create_order.php
 * Content-Type: application/json
 *
 * Nimmt eine Bestellung entgegen, validiert sie, speichert sie und
 * sendet eine E-Mail-Benachrichtigung an BMV.
 *
 * Request-Body:
 * {
 *   "customer": {
 *     "firstname":   "Maria",
 *     "lastname":    "Müller",
 *     "phone":       "+49 331 12345678",
 *     "email":       "maria@example.de",        // optional
 *     "address":     "Hauptstr. 5, 14469 Potsdam",
 *     "startdate":   "2025-03-24",              // ISO-Datum
 *     "days":        "5",                       // Liefertage pro Woche
 *     "notes":       "Bitte klingeln",          // optional
 *     "pflegekasse": true                       // optional
 *   },
 *   "selections": {
 *     "2025-03-24": { "menuNumber": 1, "addons": ["SUPPE"] },
 *     "2025-03-25": { "menuNumber": 2, "addons": [] },
 *     ...
 *   }
 * }
 *
 * Success-Response:
 * { "success": true, "order_id": "BMV-20250317-A3F2", "message": "..." }
 *
 * Error-Response:
 * { "success": false, "message": "...", "errors": { "field": "..." } }
 */

require_once __DIR__ . '/_bootstrap.php';
require_post();

/* ── Body lesen ────────────────────────────────────────────── */
$body = read_json_body();

/* ── Validierung ───────────────────────────────────────────── */
$errors = [];

// customer-Objekt prüfen
$customer = $body['customer'] ?? null;
if (!is_array($customer)) {
    api_error('Pflichtfeld "customer" fehlt oder ist kein Objekt.');
}

$firstname = sanitize($customer['firstname'] ?? '');
$lastname  = sanitize($customer['lastname']  ?? '');
$phone     = sanitize($customer['phone']     ?? '');
$address   = sanitize($customer['address']   ?? '');
$startdate = sanitize($customer['startdate'] ?? '');
$email     = sanitize($customer['email']     ?? '');
$days      = sanitize($customer['days']      ?? '');
$notes     = sanitize($customer['notes']     ?? '', 1000);
$pflegekasse = !empty($customer['pflegekasse']);

if (empty($firstname)) $errors['firstname'] = 'Vorname ist erforderlich.';
if (empty($lastname))  $errors['lastname']  = 'Nachname ist erforderlich.';
if (empty($phone))     $errors['phone']     = 'Telefonnummer ist erforderlich.';
if (empty($address))   $errors['address']   = 'Adresse ist erforderlich.';
if (empty($startdate)) $errors['startdate'] = 'Startdatum ist erforderlich.';

// Telefon: nur erlaubte Zeichen
if (!empty($phone) && !preg_match('/^[\d\s\+\-\/\(\)]{6,30}$/', $phone)) {
    $errors['phone'] = 'Ungültige Telefonnummer.';
}

// E-Mail (optional, aber wenn angegeben, muss sie gültig sein)
if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Ungültige E-Mail-Adresse.';
}

// Startdatum validieren (muss in der Zukunft liegen)
if (!empty($startdate)) {
    $start_dt = date_create($startdate);
    if (!$start_dt) {
        $errors['startdate'] = 'Ungültiges Datum.';
    } elseif ($start_dt < new DateTime('today')) {
        $errors['startdate'] = 'Startdatum muss heute oder in der Zukunft liegen.';
    }
}

// Selections validieren (optional – Bestellung kann auch ohne konkrete Menüauswahl eingehen)
$selections = $body['selections'] ?? [];
if (!is_array($selections)) {
    $selections = [];
}

// Selections bereinigen: nur gültige Datums-Keys, menuNumber als int
$clean_selections = [];
foreach ($selections as $date_key => $sel) {
    // Datum validieren
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_key)) continue;
    $dt = date_create($date_key);
    if (!$dt) continue;

    $menu_number = (int)($sel['menuNumber'] ?? 0);
    if ($menu_number < 1 || $menu_number > 9) continue;

    $addons = [];
    if (!empty($sel['addons']) && is_array($sel['addons'])) {
        foreach ($sel['addons'] as $a) {
            $code = sanitize((string)$a, 20);
            if (!empty($code)) $addons[] = $code;
        }
    }

    $clean_selections[$date_key] = [
        'menuNumber' => $menu_number,
        'addons'     => $addons,
    ];
}

// Fehler zurückgeben
if (!empty($errors)) {
    http_response_code(422);
    echo json_encode([
        'success' => false,
        'message' => 'Bitte korrigieren Sie die markierten Felder.',
        'errors'  => $errors,
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

/* ── Bestellnummer generieren ──────────────────────────────── */
$order_id = sprintf(
    'BMV-%s-%s',
    date('Ymd'),
    strtoupper(substr(md5(uniqid((string)mt_rand(), true)), 0, 6))
);

/* ── Bestellung zusammenbauen ──────────────────────────────── */
$order = [
    'order_id'   => $order_id,
    'created_at' => date('d.m.Y H:i'),
    'ip'         => $_SERVER['HTTP_X_FORWARDED_FOR']
                    ?? $_SERVER['REMOTE_ADDR']
                    ?? 'unbekannt',
    'customer'   => [
        'firstname'   => $firstname,
        'lastname'    => $lastname,
        'phone'       => $phone,
        'email'       => $email,
        'address'     => $address,
        'startdate'   => $startdate,
        'days'        => $days,
        'notes'       => $notes,
        'pflegekasse' => $pflegekasse,
    ],
    'selections' => $clean_selections,
    'status'     => 'new',
];

/* ── Speichern & E-Mail senden ─────────────────────────────── */
log_order($order);

$mail_sent = send_order_mail($order);

/* ── Erfolgsantwort ────────────────────────────────────────── */
$num_selections = count($clean_selections);
$message = $num_selections > 0
    ? "Ihre Bestellung #{$order_id} mit {$num_selections} Menü(s) wurde erfolgreich übermittelt. Wir melden uns in Kürze."
    : "Ihre Anfrage #{$order_id} wurde erfolgreich übermittelt. Wir melden uns in Kürze für die Details.";

api_json([
    'success'  => true,
    'order_id' => $order_id,
    'message'  => $message,
]);
