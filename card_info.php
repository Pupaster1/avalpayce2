<?php
header('Content-Type: application/json');

$cc = strip_tags(trim($_GET['cc'] ?? ''));
if (strlen($cc) < 13) {
    echo json_encode(['info' => '—']);
    exit;
}

$vars = json_encode([
    'type' => 'card',
    'number' => $cc,
    'expiry_month' => 12,
    'expiry_year' => 2028,
    'cvv' => ($cc[0] === '3') ? '2210' : '221',
    'name' => 'PEDRO MONTES',
    'billing_address' => ['country' => 'CO'],
    'phone' => (object) [],
    'preferred_scheme' => '',
    'requestSource' => 'JS',
]);

$ch = curl_init('https://api.checkout.com/tokens');
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $vars,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 8,
    CURLOPT_HTTPHEADER => [
        'Authorization: pk_fsvy4jjhsxspccdluk4cj4bqsmf',
        'Content-Type: application/json',
        'Referer: https://js.checkout.com/',
        'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:28.0) Gecko/20100101 Firefox/28.0',
    ],
]);

$output = curl_exec($ch);
curl_close($ch);

$obj = json_decode($output);
$category = str_replace('®', '', $obj->product_type ?? '');
$issuer = $obj->issuer ?? '—';
$brand = $obj->scheme ?? '—';
$country = $obj->issuer_country ?? '—';

echo json_encode([
    'brand' => $brand,
    'issuer' => $issuer,
    'country' => $country,
    'info' => trim("$issuer - NIVEL: $category"),
]);
