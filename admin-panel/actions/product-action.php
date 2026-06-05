<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';
require_admin();

$action = $_POST['action'] ?? '';
$product_id = $_POST['product_id'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $product_id && $action) {
    $pdo = get_db_connection();

    // Validate product exists
    $stmt = $pdo->prepare("SELECT id FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    if ($stmt->fetch()) {
        
        $new_status = null;
        
        if ($action === 'approve') {
            $new_status = 'active';
        } elseif ($action === 'remove') {
            $new_status = 'removed';
        } elseif ($action === 'pause') {
            $new_status = 'paused';
        }

        if ($new_status) {
            $update = $pdo->prepare("UPDATE products SET status = ? WHERE id = ?");
            $update->execute([$new_status, $product_id]);
        }
    }
    
    header("Location: ../pages/product-detail.php?id=" . urlencode($product_id));
    exit;
}

header("Location: ../pages/products.php");
exit;
?>
