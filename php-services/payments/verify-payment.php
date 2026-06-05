<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/constants.php';

header('Content-Type: application/json');

$order_id = $_GET['order_id'] ?? null;

if (!$order_id) {
    echo json_encode(['success' => false, 'error' => 'Missing order ID']);
    exit;
}

$pdo = getDBConnection();
$stmt = $pdo->prepare("SELECT status FROM payments WHERE order_id = ?");
$stmt->execute([$order_id]);
$payment = $stmt->fetch();

if ($payment) {
    echo json_encode([
        'success' => true,
        'payment_status' => $payment['status']
    ]);
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Payment record not found'
    ]);
}
?>
