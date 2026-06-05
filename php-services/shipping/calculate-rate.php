<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/constants.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$weight = filter_input(INPUT_GET, 'weight', FILTER_VALIDATE_FLOAT);
$from = filter_input(INPUT_GET, 'from', FILTER_SANITIZE_STRING);
$to = filter_input(INPUT_GET, 'to', FILTER_SANITIZE_STRING);

if ($weight === false || $weight === null) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid or missing weight parameter']);
    exit;
}

// Basic mocked shipping calculation logic based on weight
$rate = SHIPPING_BASE_RATE + ($weight * SHIPPING_RATE_PER_KG);

// Simulate cross-province surcharge if 'from' and 'to' strings differ slightly
// In a real app this would compare actual provinces
if ($from && $to && strtolower(trim($from)) !== strtolower(trim($to))) {
    $rate += SHIPPING_PROVINCE_SURCHARGE;
}

echo json_encode([
    'success' => true,
    'data' => [
        'base_rate' => SHIPPING_BASE_RATE,
        'weight_charge' => ($weight * SHIPPING_RATE_PER_KG),
        'surcharge' => ($from && $to && strtolower(trim($from)) !== strtolower(trim($to))) ? SHIPPING_PROVINCE_SURCHARGE : 0,
        'total' => round($rate, 2),
        'currency' => 'ZAR'
    ]
]);
?>
