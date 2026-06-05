<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';
require_admin();

$page_title = 'Dashboard Overview';
$pdo = get_db_connection();

// Fetch basic stats
$users_count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$orders_count = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$products_count = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$revenue = $pdo->query("SELECT SUM(commission_amount) FROM orders WHERE status = 'completed'")->fetchColumn() ?: 0;

include __DIR__ . '/../includes/header.php';
?>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-0 bg-primary text-white">
            <div class="card-body">
                <h6 class="card-title text-uppercase text-white-50">Total Users</h6>
                <h2 class="mb-0"><?php echo number_format($users_count); ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 bg-success text-white">
            <div class="card-body">
                <h6 class="card-title text-uppercase text-white-50">Platform Revenue</h6>
                <h2 class="mb-0">R <?php echo number_format($revenue, 2); ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 bg-warning text-dark">
            <div class="card-body">
                <h6 class="card-title text-uppercase text-black-50">Total Orders</h6>
                <h2 class="mb-0"><?php echo number_format($orders_count); ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 bg-info text-white">
            <div class="card-body">
                <h6 class="card-title text-uppercase text-white-50">Active Products</h6>
                <h2 class="mb-0"><?php echo number_format($products_count); ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0">Recent Orders</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $recent_orders = $pdo->query("SELECT id, order_number, created_at, status, total FROM orders ORDER BY created_at DESC LIMIT 5")->fetchAll();
                            foreach ($recent_orders as $order) {
                                $status_class = match($order['status']) {
                                    'completed', 'delivered' => 'bg-success',
                                    'pending_payment', 'processing' => 'bg-warning text-dark',
                                    'cancelled', 'refunded' => 'bg-danger',
                                    default => 'bg-secondary'
                                };
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['order_number']); ?></td>
                                <td><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
                                <td><span class="badge <?php echo $status_class; ?>"><?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $order['status']))); ?></span></td>
                                <td>R <?php echo number_format($order['total'], 2); ?></td>
                                <td><a href="order-detail.php?id=<?php echo urlencode($order['id']); ?>" class="btn btn-sm btn-outline-primary">View</a></td>
                            </tr>
                            <?php } ?>
                            <?php if (empty($recent_orders)): ?>
                            <tr><td colspan="5" class="text-center py-3 text-muted">No recent orders found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
