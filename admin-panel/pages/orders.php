<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';
require_admin();

$page_title = 'Order Management';
$pdo = get_db_connection();

$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

$query = "SELECT o.*, b.name as buyer_name, s.name as seller_name 
          FROM orders o 
          JOIN users b ON o.buyer_id = b.id 
          JOIN users s ON o.seller_id = s.id";

$params = [];
if ($status_filter) {
    $query .= " WHERE o.status = ?";
    $params[] = $status_filter;
}
$query .= " ORDER BY o.created_at DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$orders = $stmt->fetchAll();

include __DIR__ . '/../includes/header.php';
?>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">All Orders</h5>
        <form method="GET" class="d-flex">
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                <option value="pending_payment" <?php echo $status_filter === 'pending_payment' ? 'selected' : ''; ?>>Pending Payment</option>
                <option value="paid" <?php echo $status_filter === 'paid' ? 'selected' : ''; ?>>Paid</option>
                <option value="processing" <?php echo $status_filter === 'processing' ? 'selected' : ''; ?>>Processing</option>
                <option value="shipped" <?php echo $status_filter === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                <option value="delivered" <?php echo $status_filter === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                <option value="completed" <?php echo $status_filter === 'completed' ? 'selected' : ''; ?>>Completed</option>
                <option value="cancelled" <?php echo $status_filter === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                <option value="refunded" <?php echo $status_filter === 'refunded' ? 'selected' : ''; ?>>Refunded</option>
            </select>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Order #</th>
                        <th>Buyer</th>
                        <th>Seller</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($order['order_number']); ?></strong></td>
                        <td><a href="user-detail.php?id=<?php echo urlencode($order['buyer_id']); ?>"><?php echo htmlspecialchars($order['buyer_name']); ?></a></td>
                        <td><a href="user-detail.php?id=<?php echo urlencode($order['seller_id']); ?>"><?php echo htmlspecialchars($order['seller_name']); ?></a></td>
                        <td><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
                        <td>R <?php echo number_format($order['total'], 2); ?></td>
                        <td>
                            <?php
                            $status_class = match($order['status']) {
                                'completed', 'delivered' => 'bg-success',
                                'pending_payment', 'processing' => 'bg-warning text-dark',
                                'cancelled', 'refunded', 'refund_requested' => 'bg-danger',
                                default => 'bg-secondary'
                            };
                            ?>
                            <span class="badge <?php echo $status_class; ?>">
                                <?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $order['status']))); ?>
                            </span>
                        </td>
                        <td>
                            <a href="order-detail.php?id=<?php echo urlencode($order['id']); ?>" class="btn btn-sm btn-outline-primary">View details</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($orders)): ?>
                    <tr><td colspan="7" class="text-center py-4 text-muted">No orders found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
