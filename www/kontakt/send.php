<?php
/**
 * POST /kontakt/send.php
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/api/_bootstrap.php';
require_post();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/PHPMailer/src/Exception.php';
require $_SERVER['DOCUMENT_ROOT'] . '/vendor/PHPMailer/src/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'] . '/vendor/PHPMailer/src/SMTP.php';

/* ── Body einlesen — JSON oder Form ────────────────────────── */
$raw = [];  // FIX: immer initialisieren

$content_type = $_SERVER['CONTENT_TYPE'] ?? '';

if (stripos($content_type, 'application/json') !== false) {
    $raw   = read_json_body();           // bricht bei leerem Body selbst ab
    $get   = fn(string $k) => sanitize($raw[$k] ?? '');
    $dsgvo = !empty($raw['datenschutz']);
} else {
    if (empty($_POST)) {
        api_error('Kein Request-Body gefunden.', 400);
    }
    $get   = fn(string $k) => sanitize($_POST[$k] ?? '');
    $dsgvo = !empty($_POST['datenschutz']);
}

$name    = $get('name');
$email   = $get('email');
$phone   = $get('telefon');
$subject = $get('betreff');

// FIX: $raw ist jetzt immer definiert — kein Undefined variable mehr
$message = sanitize((string)($_POST['nachricht'] ?? $raw['nachricht'] ?? ''), 2000);

/* ── Validierung ───────────────────────────────────────────── */
$errors = [];

if (mb_strlen($name) < 2)
    $errors['name'] = 'Name ist erforderlich.';
if (empty($email))
    $errors['email'] = 'E-Mail ist erforderlich.';
elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))
    $errors['email'] = 'Ungültige E-Mail-Adresse.';
if (mb_strlen($message) < 10)
    $errors['nachricht'] = 'Nachricht ist erforderlich (min. 10 Zeichen).';
if (!$dsgvo)
    $errors['datenschutz'] = 'Bitte stimmen Sie der Datenschutzerklärung zu.';

if (!empty($errors)) {
    http_response_code(422);
    echo json_encode([
        'success' => false,
        'message' => 'Bitte korrigieren Sie die markierten Felder.',
        'errors'  => $errors,
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

/* ── Honeypot ──────────────────────────────────────────────── */
$honeypot = sanitize((string)($_POST['website'] ?? $raw['website'] ?? ''));
if (!empty($honeypot)) {
    api_json(['success' => true]);  // Bot — stille Antwort
}

/* ── Mailtext ──────────────────────────────────────────────── */
$subj_line = !empty($subject)
    ? 'Kontaktanfrage: ' . $subject
    : 'Kontaktanfrage über bmv-kantinen.de';

$body_text = <<<TEXT
Neue Kontaktanfrage über www.bmv-kantinen.de
============================================

Name    : {$name}
E-Mail  : {$email}
Telefon : {$phone}
Betreff : {$subject}

Nachricht:
{$message}

============================================
IP: {$_SERVER['REMOTE_ADDR']}
Browser: {$_SERVER['HTTP_USER_AGENT']}
TEXT;

/* ── PHPMailer ─────────────────────────────────────────────── */
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = getenv('MAIL_HOST')     ?: 'server11.greatnet.de';
    $mail->Port       = (int)(getenv('MAIL_PORT') ?: 587);
    $mail->SMTPAuth   = false;
    $mail->Username   = getenv('MAIL_USERNAME') ?: '';
    $mail->Password   = getenv('MAIL_PASSWORD') ?: '';
    $mail->SMTPSecure = '';
    $mail->CharSet    = 'UTF-8';

    $mail->setFrom(
        getenv('MAIL_FROM') ?: 'info@bmv-kantinen.de',
        'BMV-Menüdienst'
    );
    $mail->addAddress(
        getenv('MAIL_TO') ?: 'info@bmv-kantinen.de'
    );
    $mail->addReplyTo($email, $name);

    $mail->Subject = $subj_line;
    $mail->Body    = $body_text;

    $sent = $mail->send();

} catch (Exception $e) {
    error_log('[BMV Kontakt] PHPMailer Fehler: ' . $e->getMessage());
    $sent = false;
}

/* ── Log ───────────────────────────────────────────────────── */
$log_line = sprintf(
    "[%s] Kontakt von %s <%s> Tel: %s Betreff: %s Mail: %s\n",
    date('Y-m-d H:i:s'),
    $name, $email, $phone,
    $subject ?: '–',
    $sent ? 'OK' : 'FEHLER'
);

$log_file = $_SERVER['DOCUMENT_ROOT'] . '/data/kontakt.log';
if (is_writable(dirname($log_file))) {
    file_put_contents($log_file, $log_line, FILE_APPEND | LOCK_EX);
}

/* ── Response ──────────────────────────────────────────────── */
if (!$sent) {
    api_error('Die Nachricht konnte leider nicht gesendet werden.', 500);
}

api_json([
    'success' => true,
    'message' => 'Vielen Dank für Ihre Nachricht! Wir melden uns in Kürze.',
]);