<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit(json_encode(['error' => 'Method not allowed']));
}

$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$order_id = $input['order_id'] ?? null;

if (!$order_id) {
    http_response_code(400);
    exit(json_encode(['error' => 'Order ID is required.']));
}

$pdo = getDBConnection();

try {
    $pdo->beginTransaction();
    
    // Check if the order is delivered
    $stmt = $pdo->prepare("SELECT status, escrow_status FROM orders WHERE id = ?");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch();
    
    if (!$order) {
        throw new Exception("Order not found.");
    }
    
    if ($order['status'] !== 'delivered' && $order['status'] !== 'completed') {
        throw new Exception("Order must be delivered before escrow can be released.");
    }
    
    if ($order['escrow_status'] === 'released') {
        throw new Exception("Escrow already released for this order.");
    }
    
    // Release escrow
    $update = $pdo->prepare("UPDATE orders SET escrow_status = 'released', status = 'completed' WHERE id = ?");
    $update->execute([$order_id]);
    
    // In a real app, this is where we would trigger a payout API to the seller's bank account
    
    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Escrow funds have been successfully released to the seller.']);
} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
