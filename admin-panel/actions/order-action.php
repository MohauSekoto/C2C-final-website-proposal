<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';
require_admin();

$action = $_POST['action'] ?? '';
$order_id = $_POST['order_id'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $order_id && $action) {
    $pdo = get_db_connection();

    if ($action === 'update_status') {
        $new_status = $_POST['status'] ?? '';
        
        if (in_array($new_status, ['cancelled', 'refunded', 'completed'])) {
            $update = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
            $update->execute([$new_status, $order_id]);
        }
    }
    
    header("Location: ../pages/order-detail.php?id=" . urlencode($order_id));
    exit;
}

header("Location: ../pages/orders.php");
exit;
?>
