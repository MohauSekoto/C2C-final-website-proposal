<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';
require_admin();

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: orders.php");
    exit;
}

$pdo = get_db_connection();
$stmt = $pdo->prepare("SELECT o.*, b.name as buyer_name, b.email as buyer_email, 
                       s.name as seller_name, s.email as seller_email 
                       FROM orders o 
                       JOIN users b ON o.buyer_id = b.id 
                       JOIN users s ON o.seller_id = s.id 
                       WHERE o.id = ?");
$stmt->execute([$id]);
$order = $stmt->fetch();

if (!$order) {
    die("Order not found.");
}

// Fetch order items
$items_stmt = $pdo->prepare("SELECT oi.*, p.title, p.images 
                             FROM order_items oi 
                             JOIN products p ON oi.product_id = p.id 
                             WHERE oi.order_id = ?");
$items_stmt->execute([$id]);
$items = $items_stmt->fetchAll();

$page_title = "Order Details: " . htmlspecialchars($order['order_number']);
include __DIR__ . '/../includes/header.php';
?>

<div class="row">
    <div class="col-md-8 mb-4">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Order Items</h5>
                <span class="badge bg-secondary"><?php echo count($items); ?> items</span>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): 
                            $images = json_decode($item['images'], true);
                            $img_url = !empty($images) ? $images[0] : 'https://placehold.co/50x50';
                        ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="<?php echo htmlspecialchars($img_url); ?>" width="40" height="40" class="rounded me-3" style="object-fit: cover;">
                                    <div>
                                        <h6 class="mb-0"><?php echo htmlspecialchars($item['title']); ?></h6>
                                        <small class="text-muted">ID: <?php echo htmlspecialchars($item['product_id']); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>R <?php echo number_format($item['unit_price'], 2); ?></td>
                            <td><?php echo (int)$item['quantity']; ?></td>
                            <td class="text-end fw-bold">R <?php echo number_format($item['total_price'], 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal:</span>
                    <span>R <?php echo number_format($order['subtotal'], 2); ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Shipping:</span>
                    <span>R <?php echo number_format($order['shippingCost'], 2); ?></span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="fw-bold">Total:</span>
                    <span class="fw-bold fs-5">R <?php echo number_format($order['total'], 2); ?></span>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title border-bottom pb-2 mb-3">Shipping Details</h5>
                <?php $addr = json_decode($order['shippingAddress'], true); ?>
                <?php if ($addr): ?>
                    <p class="mb-1"><strong><?php echo htmlspecialchars($addr['fullName'] ?? $order['buyer_name']); ?></strong></p>
                    <p class="mb-1"><?php echo htmlspecialchars($addr['streetAddress'] ?? ''); ?></p>
                    <p class="mb-1"><?php echo htmlspecialchars(($addr['city'] ?? '') . ', ' . ($addr['province'] ?? '') . ' ' . ($addr['postalCode'] ?? '')); ?></p>
                    <p class="mb-0 mt-2"><i class="bi bi-telephone"></i> <?php echo htmlspecialchars($addr['phone'] ?? 'N/A'); ?></p>
                <?php else: ?>
                    <p class="text-muted">No structured shipping address found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h5 class="card-title border-bottom pb-2 mb-3">Status Overview</h5>
                <p class="mb-2">
                    <strong>Order Status:</strong> 
                    <?php
                    $status_class = match($order['status']) {
                        'completed', 'delivered' => 'bg-success',
                        'pending_payment', 'processing' => 'bg-warning text-dark',
                        'cancelled', 'refunded' => 'bg-danger',
                        default => 'bg-secondary'
                    };
                    ?>
                    <span class="badge <?php echo $status_class; ?> float-end"><?php echo ucfirst(str_replace('_', ' ', $order['status'])); ?></span>
                </p>
                <p class="mb-2"><strong>Escrow Status:</strong> <span class="badge bg-secondary float-end"><?php echo ucfirst($order['escrowStatus']); ?></span></p>
                <hr>
                <form action="../actions/order-action.php" method="POST">
                    <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['id']); ?>">
                    <div class="mb-3">
                        <label class="form-label text-muted small">Update Status (Admin Override)</label>
                        <select name="status" class="form-select form-select-sm mb-2">
                            <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                            <option value="refunded" <?php echo $order['status'] === 'refunded' ? 'selected' : ''; ?>>Refunded</option>
                            <option value="completed" <?php echo $order['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                        </select>
                        <button type="submit" name="action" value="update_status" class="btn btn-sm btn-outline-primary w-100">Force Update Status</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h5 class="card-title border-bottom pb-2 mb-3">Financials</h5>
                <p class="mb-2 d-flex justify-content-between"><span class="text-muted">Commission Rate:</span> <span><?php echo (float)$order['commissionRate']; ?>%</span></p>
                <p class="mb-2 d-flex justify-content-between"><span class="text-muted">Platform Fee:</span> <span>R <?php echo number_format($order['commissionAmount'], 2); ?></span></p>
                <p class="mb-0 d-flex justify-content-between"><span class="text-muted">Seller Payout:</span> <strong>R <?php echo number_format($order['total'] - $order['commissionAmount'], 2); ?></strong></p>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title border-bottom pb-2 mb-3">Parties Involved</h5>
                <div class="mb-3">
                    <label class="text-muted text-uppercase small fw-bold">Buyer</label>
                    <p class="mb-0"><a href="user-detail.php?id=<?php echo urlencode($order['buyer_id']); ?>"><?php echo htmlspecialchars($order['buyer_name']); ?></a></p>
                    <small class="text-muted"><?php echo htmlspecialchars($order['buyer_email']); ?></small>
                </div>
                <div>
                    <label class="text-muted text-uppercase small fw-bold">Seller</label>
                    <p class="mb-0"><a href="user-detail.php?id=<?php echo urlencode($order['seller_id']); ?>"><?php echo htmlspecialchars($order['seller_name']); ?></a></p>
                    <small class="text-muted"><?php echo htmlspecialchars($order['seller_email']); ?></small>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
