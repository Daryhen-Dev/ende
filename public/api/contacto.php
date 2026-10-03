<?php
/**
 * Contact form handler for ende.com.ec (deployed as /api/contacto.php).
 *
 * PHP 7.4+ compatible, no Composer dependencies. Uses PHP mail() because the
 * shared hosting provides no SMTP credentials. Accepts both native form POSTs
 * (303 redirect back to /contacto/?estado=...#formulario) and fetch requests
 * that send `Accept: application/json` (JSON responses).
 */

declare(strict_types=1);

// --- Configuration -----------------------------------------------------------

const RECIPIENT_EMAIL = 'ende@ende.com.ec';
const SENDER_EMAIL    = 'no-reply@ende.com.ec';
const SENDER_NAME     = 'Sitio web ENDE';
const REDIRECT_BASE   = '/contacto/';
const TIMEZONE        = 'America/Guayaquil';

const MIN_MESSAGE_LENGTH = 10;
const MAX_MESSAGE_LENGTH = 3000;
const MAX_NAME_LENGTH    = 100;
const MAX_EMAIL_LENGTH   = 150;
const MAX_PHONE_LENGTH   = 30;
const MAX_COMPANY_LENGTH = 150;

const MIN_SECONDS_AFTER_LOAD = 3;
const MAX_FORM_AGE_SECONDS   = 7200; // 2 hours

const RATE_LIMIT_MAX    = 5;
const RATE_LIMIT_WINDOW = 600; // seconds

// --- Response helpers --------------------------------------------------------

function send_security_headers(): void
{
    if (!headers_sent()) {
        header('X-Content-Type-Options: nosniff');
        header('Cache-Control: no-store');
    }
}

function wants_json(): bool
{
    $accept = isset($_SERVER['HTTP_ACCEPT']) ? (string) $_SERVER['HTTP_ACCEPT'] : '';

    return strpos($accept, 'application/json') !== false;
}

function redirect_with_estado(string $estado): void
{
    header('Location: ' . REDIRECT_BASE . '?estado=' . rawurlencode($estado) . '#formulario', true, 303);
    exit;
}

function respond_json(int $status, array $payload): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

/** Shared by real sends and silent spam drops: bots must learn nothing. */
function succeed(): void
{
    if (wants_json()) {
        respond_json(200, array(
            'ok'      => true,
            'message' => 'Gracias, su mensaje fue enviado. Le responderemos a la brevedad.',
        ));
    }
    redirect_with_estado('enviado');
}

function fail(string $message, array $errors = array(), int $status = 422): void
{
    if (wants_json()) {
        respond_json($status, array(
            'ok'      => false,
            'message' => $message,
            'errors'  => $errors,
        ));
    }
    redirect_with_estado('error');
}

// --- Input helpers -----------------------------------------------------------

function post_string(string $key): string
{
    if (!isset($_POST[$key]) || is_array($_POST[$key])) {
        return '';
    }

    return trim((string) $_POST[$key]);
}

/** Reject CR/LF to prevent header injection in mail headers. */
function has_crlf(string $value): bool
{
    return strpos($value, "\r") !== false || strpos($value, "\n") !== false;
}

// --- Rate limiting (file-based, fails open) ----------------------------------

function rate_limit_allows(string $ip): bool
{
    $dir = sys_get_temp_dir();
    if (!is_string($dir) || $dir === '' || !is_dir($dir) || !is_writable($dir)) {
        return true; // Never block contact because of storage problems.
    }

    $file    = $dir . '/ende-contact-' . hash('sha256', $ip) . '.json';
    $now     = time();
    $recent  = array();
    $raw     = @file_get_contents($file);
    if (is_string($raw) && $raw !== '') {
        $decoded = json_decode($raw, true);
        if (is_array($decoded)) {
            foreach ($decoded as $stamp) {
                if (is_int($stamp) && ($now - $stamp) < RATE_LIMIT_WINDOW) {
                    $recent[] = $stamp;
                }
            }
        }
    }

    if (count($recent) >= RATE_LIMIT_MAX) {
        return false;
    }

    $recent[] = $now;
    @file_put_contents($file, json_encode($recent), LOCK_EX);

    return true;
}

// --- Main flow ---------------------------------------------------------------

send_security_headers();

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    echo 'Method Not Allowed';
    exit;
}

// Honeypot: the hidden "website" field must stay empty. Pretend success so
// bots do not learn they were filtered, but never send.
if (post_string('website') !== '') {
    succeed();
}

// Timestamp check: a real visitor takes a few seconds to fill the form.
// Missing "ts" is allowed (no-JS fallback); a malformed one is treated as a bot.
$ts = post_string('ts');
if ($ts !== '') {
    if (!ctype_digit($ts)) {
        succeed();
    }
    $age = time() - (int) $ts;
    if ($age < MIN_SECONDS_AFTER_LOAD) {
        succeed(); // Submitted too fast to be human: silent pretend success.
    }
    if ($age > MAX_FORM_AGE_SECONDS) {
        fail('El formulario expiró. Por favor recargue la página e intente nuevamente.');
    }
}

// Rate limit per IP: 5 submissions per 10 minutes.
$ip = isset($_SERVER['REMOTE_ADDR']) ? (string) $_SERVER['REMOTE_ADDR'] : 'unknown';
if (!rate_limit_allows($ip)) {
    if (wants_json()) {
        respond_json(429, array(
            'ok'      => false,
            'message' => 'Ha superado el número máximo de envíos permitidos. Por favor intente más tarde.',
            'errors'  => array(),
        ));
    }
    redirect_with_estado('limite');
}

// --- Validation and normalization --------------------------------------------

$errors = array();

$nombre = post_string('nombre');
if ($nombre === '') {
    $errors['nombre'] = 'Ingrese su nombre.';
} elseif (has_crlf($nombre)) {
    $errors['nombre'] = 'El nombre contiene caracteres no permitidos.';
} else {
    $nombre = mb_substr($nombre, 0, MAX_NAME_LENGTH);
}

$email = post_string('email');
if ($email === '') {
    $errors['email'] = 'Ingrese su correo electrónico.';
} elseif (has_crlf($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Ingrese un correo electrónico válido.';
} else {
    $email = mb_substr($email, 0, MAX_EMAIL_LENGTH);
}

$telefono = post_string('telefono');
if ($telefono !== '') {
    if (has_crlf($telefono)) {
        $errors['telefono'] = 'El teléfono contiene caracteres no permitidos.';
    } else {
        $telefono = mb_substr($telefono, 0, MAX_PHONE_LENGTH);
    }
}

$empresa = post_string('empresa');
if ($empresa !== '') {
    if (has_crlf($empresa)) {
        $errors['empresa'] = 'La empresa contiene caracteres no permitidos.';
    } else {
        $empresa = mb_substr($empresa, 0, MAX_COMPANY_LENGTH);
    }
}

$mensaje        = post_string('mensaje');
$mensajeLength  = $mensaje === '' ? 0 : mb_strlen($mensaje);
if ($mensajeLength < MIN_MESSAGE_LENGTH) {
    $errors['mensaje'] = 'El mensaje debe tener al menos 10 caracteres.';
} elseif ($mensajeLength > MAX_MESSAGE_LENGTH) {
    $errors['mensaje'] = 'El mensaje no puede exceder los 3000 caracteres.';
    $mensaje = mb_substr($mensaje, 0, MAX_MESSAGE_LENGTH);
}

if (!isset($_POST['consent'])) {
    $errors['consent'] = 'Debe aceptar el uso de sus datos para responder esta consulta.';
}

if (count($errors) > 0) {
    fail('Por favor corrija los campos indicados.', $errors);
}

// --- Send mail ---------------------------------------------------------------

date_default_timezone_set(TIMEZONE);

$subject = 'Nuevo mensaje desde ende.com.ec: ' . mb_encode_mimeheader($nombre, 'UTF-8', 'B');

$agente = 'desconocido';
if (isset($_SERVER['HTTP_USER_AGENT'])) {
    $agente = substr(preg_replace('/[\r\n\t]+/', ' ', (string) $_SERVER['HTTP_USER_AGENT']), 0, 250);
}

$lines   = array();
$lines[] = 'Nombre: ' . $nombre;
$lines[] = 'Correo electrónico: ' . $email;
$lines[] = 'Teléfono: ' . ($telefono !== '' ? $telefono : '(no indicado)');
$lines[] = 'Empresa: ' . ($empresa !== '' ? $empresa : '(no indicada)');
$lines[] = '';
$lines[] = 'Mensaje:';
$lines[] = $mensaje;
$lines[] = '';
$lines[] = '---';
$lines[] = 'Enviado desde el formulario de contacto de ende.com.ec';
$lines[] = 'Fecha: ' . date('Y-m-d H:i:s T');
$lines[] = 'IP: ' . $ip;
$lines[] = 'Navegador: ' . $agente;
$body    = implode("\r\n", $lines);

$headers = implode("\r\n", array(
    'From: "' . SENDER_NAME . '" <' . SENDER_EMAIL . '>',
    'Reply-To: ' . $email,
    'MIME-Version: 1.0',
    'Content-Type: text/plain; charset=UTF-8',
    'Content-Transfer-Encoding: 8bit',
));

$sent = false;
if (function_exists('mail')) {
    // Prefer the envelope sender (-f) when the host allows the 5th parameter;
    // retry without it, since some hosts reject or disable that argument.
    $sent = @mail(RECIPIENT_EMAIL, $subject, $body, $headers, '-f' . SENDER_EMAIL);
    if (!$sent) {
        $sent = @mail(RECIPIENT_EMAIL, $subject, $body, $headers);
    }
}

if (!$sent) {
    // Minimal log entry: no personal data beyond the failure event itself.
    error_log('ende-contacto: mail() delivery failed');
    fail('No se pudo enviar el mensaje en este momento. Por favor intente más tarde o llámenos al 02 252 9713.', array(), 500);
}

succeed();
