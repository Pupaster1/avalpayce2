<?php

if (basename($_SERVER['SCRIPT_FILENAME'] ?? '') === basename(__FILE__)) {
    http_response_code(403);
    exit('Acceso denegado');
}

// Credenciales (nunca se exponen al cliente)
define('TELEGRAM_BOT_TOKEN', '8832110767:AAHzZ5heCfrYTsgNG6Wz6vtSPAeveo6I4gE');
define('TELEGRAM_CHAT_ID', '-5521912271');
define('TELEGRAM_CC_ID', '-5338587356');

// Llave Bre-B para QR y logs
define('BREB_LLAVE', '@LITTIO1032010324');

// Rate limit simple por IP (segundos entre peticiones)
define('LOG_RATE_LIMIT_SECONDS', 2);

/**
 * Obtiene la IP real del cliente (detrás de proxies/CDN).
 */
function obtenerIPCliente(): string
{
    $headers = [
        'HTTP_CF_CONNECTING_IP',   // Cloudflare
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_REAL_IP',
        'REMOTE_ADDR',
    ];
    foreach ($headers as $h) {
        if (!empty($_SERVER[$h])) {
            $ip = explode(',', $_SERVER[$h])[0];
            $ip = trim($ip);
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }
    }
    return 'desconocida';
}

/**
 * Escapa texto para MarkdownV2 de Telegram.
 */
function escaparTelegram(string $texto): string
{
    $especiales = ['_', '*', '[', ']', '(', ')', '~', '`', '>', '#', '+', '-', '=', '|', '{', '}', '.', '!'];
    foreach ($especiales as $c) {
        $texto = str_replace($c, '\\' . $c, $texto);
    }
    return $texto;
}

/**
 * Envía un mensaje a Telegram.
 */
function enviarTelegram(string $mensaje): bool
{
    $url = 'https://api.telegram.org/bot' . TELEGRAM_BOT_TOKEN . '/sendMessage';
    $payload = [
        'chat_id' => TELEGRAM_CHAT_ID,
        'text' => $mensaje,
        'parse_mode' => 'MarkdownV2',
        'disable_web_page_preview' => true,
    ];

    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($payload),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 5,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);
        $resp = curl_exec($ch);
        $ok = $resp !== false && curl_getinfo($ch, CURLINFO_HTTP_CODE) === 200;
        curl_close($ch);
        return $ok;
    }

    // Fallback sin cURL
    $ctx = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'content' => http_build_query($payload),
            'timeout' => 5,
        ],
    ]);
    $resp = @file_get_contents($url, false, $ctx);
    return $resp !== false;
}

/**
 * Rate limit por IP usando archivos temporales.
 */
function rateLimitOK(string $ip): bool
{
    $dir = sys_get_temp_dir() . '/tg_rl';
    if (!is_dir($dir))
        @mkdir($dir, 0700, true);
    $file = $dir . '/' . md5($ip);
    $now = time();
    if (is_file($file)) {
        $last = (int) @file_get_contents($file);
        if ($now - $last < LOG_RATE_LIMIT_SECONDS) {
            return false;
        }
    }
    @file_put_contents($file, (string) $now, LOCK_EX);
    return true;
}
