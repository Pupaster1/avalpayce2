<?php
/**
 * Endpoint de búsqueda de convenios.
 * Lee el catálogo final y devuelve resultados compatibles con el buscador
 * actual y con el flujo dinámico del paso 1.
 */

header('Content-Type: application/json; charset=utf-8');

function normalizar(string $texto): string
{
    $texto = mb_strtoupper($texto, 'UTF-8');

    if (class_exists('Normalizer')) {
        $descompuesto = Normalizer::normalize($texto, Normalizer::FORM_D);
        if ($descompuesto !== false) {
            $texto = preg_replace('/\p{Mn}+/u', '', $descompuesto);
        }
    } else {
        $transliterado = @iconv('UTF-8', 'ASCII//TRANSLIT', $texto);
        if ($transliterado !== false) {
            $texto = $transliterado;
        }
    }

    $texto = preg_replace('/[^A-Z0-9]+/u', ' ', $texto);
    $texto = preg_replace('/\s+/u', ' ', (string) $texto);

    return trim((string) $texto);
}

function tokenizar(string $texto): array
{
    $textoNormalizado = normalizar($texto);

    if ($textoNormalizado === '') {
        return [];
    }

    $tokens = preg_split('/\s+/u', $textoNormalizado) ?: [];

    return array_values(array_filter($tokens, static function ($token) {
        return $token !== '';
    }));
}

$termino = isset($_GET['q']) ? trim((string) $_GET['q']) : '';
$limite = isset($_GET['limite']) ? (int) $_GET['limite'] : 10;
$limite = max(1, min(100, $limite));

if (mb_strlen($termino, 'UTF-8') < 2) {
    echo json_encode([]);
    exit;
}

$rutaJson = __DIR__ . '/data/resultados_final.json';
$contenido = @file_get_contents($rutaJson);

if ($contenido === false) {
    http_response_code(500);
    echo json_encode(['error' => 'No se pudo leer el catálogo de convenios']);
    exit;
}

$convenios = json_decode($contenido, true);

if (!is_array($convenios)) {
    http_response_code(500);
    echo json_encode(['error' => 'El catálogo de convenios no tiene un formato válido']);
    exit;
}

$q = normalizar($termino);
$tokensBusqueda = tokenizar($termino);
$resultados = [];
$coincidencias = [];

foreach ($convenios as $convenio) {
    $nombre = (string) ($convenio['nombre'] ?? '');
    $nombreNormalizado = normalizar($nombre);

    $coincideFrase = $q !== '' && strpos($nombreNormalizado, $q) !== false;
    $coincideTokens = !empty($tokensBusqueda);

    if (!$coincideFrase) {
        foreach ($tokensBusqueda as $token) {
            if (strpos($nombreNormalizado, $token) === false) {
                $coincideTokens = false;
                break;
            }
        }
    }

    if ($coincideFrase || $coincideTokens) {
        $puntaje = $coincideFrase ? 1000 : 500;
        $puntaje += count($tokensBusqueda) * 10;

        if ($coincideFrase) {
            $posicionFrase = strpos($nombreNormalizado, $q);
            $puntaje -= ($posicionFrase === false ? 0 : $posicionFrase);
        } else {
            foreach ($tokensBusqueda as $token) {
                $posicionToken = strpos($nombreNormalizado, $token);
                $puntaje -= ($posicionToken === false ? 0 : $posicionToken);
            }
        }

        $coincidencias[] = [
            'puntaje' => $puntaje,
            'resultado' => [
            'idConv' => $convenio['idConv'] ?? null,
            'nombre' => $nombre,
            'camposIdentificacion' => is_array($convenio['camposIdentificacion'] ?? null)
                ? $convenio['camposIdentificacion']
                : [],
            'url' => $convenio['url'] ?? '',
            'ID' => $convenio['idConv'] ?? null,
            'Nombre' => $nombre,
            'Categoria' => $convenio['categoria'] ?? '',
            ],
        ];
    }
}

usort($coincidencias, static function ($a, $b) {
    return $b['puntaje'] <=> $a['puntaje'];
});

$coincidencias = array_slice($coincidencias, 0, $limite);

foreach ($coincidencias as $coincidencia) {
    $resultados[] = $coincidencia['resultado'];
}

echo json_encode($resultados, JSON_UNESCAPED_UNICODE);
