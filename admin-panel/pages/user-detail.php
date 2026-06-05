<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';
require_admin();

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: users.php");
    exit;
}

$pdo = get_db_connection();
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    die("User not found.");
}

$page_title = "User Details: " . htmlspecialchars($user['name']);
include __DIR__ . '/../includes/header.php';
?>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body text-center pt-5">
                <div class="bg-secondary rounded-circle d-flex justify-content-center align-items-center text-white mx-auto mb-3" style="width: 80px; height: 80px; font-size: 32px;">
                    <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                </div>
                <h4 class="mb-1"><?php echo htmlspecialchars($user['name']); ?></h4>
                <p class="text-muted mb-3"><?php echo htmlspecialchars($user['email']); ?></p>
                <span class="badge bg-<?php echo match($user['role']) { 'admin' => 'danger', 'seller' => 'info text-dark', default => 'secondary' }; ?> px-3 py-2 mb-4">
                    <?php echo ucfirst($user['role']); ?>
                </span>
                
                <hr>
                
                <div class="text-start mt-4">
                    <p class="mb-2"><small class="text-muted text-uppercase fw-bold">User ID</small><br>
                    <span class="text-break" style="font-family: monospace;"><?php echo htmlspecialchars($user['id']); ?></span></p>
                    <p class="mb-2"><small class="text-muted text-uppercase fw-bold">Joined On</small><br>
                    <?php echo date('F j, Y, g:i a', strtotime($user['created_at'])); ?></p>
                    <p class="mb-0"><small class="text-muted text-uppercase fw-bold">Email Verified</small><br>
                    <?php echo $user['email_verified'] ? '<span class="text-success"><i class="bi bi-check-circle-fill"></i> Yes</span>' : '<span class="text-warning"><i class="bi bi-exclamation-circle-fill"></i> No</span>'; ?></p>
                </div>
            </div>
            <div class="card-footer bg-white text-center border-top-0 pb-4">
                <?php if ($user['id'] !== $_SESSION['admin_id']): ?>
                <form action="../actions/user-action.php" method="POST" onsubmit="return confirm('Change user role?');" class="mb-2">
                    <input type="hidden" name="action" value="change_role">
                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">
                    <div class="input-group input-group-sm">
                        <select name="new_role" class="form-select">
                            <option value="buyer" <?php echo $user['role'] === 'buyer' ? 'selected' : ''; ?>>Buyer</option>
                            <option value="seller" <?php echo $user['role'] === 'seller' ? 'selected' : ''; ?>>Seller</option>
                            <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                        </select>
                        <button class="btn btn-outline-primary" type="submit">Update Role</button>
                    </div>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-8 mb-4">
        <?php if ($user['role'] === 'seller'): ?>
            <?php
            $stmt = $pdo->prepare("SELECT * FROM seller_profiles WHERE user_id = ?");
            $stmt->execute([$user['id']]);
            $seller_profile = $stmt->fetch();
            ?>
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-shop me-2"></i> Seller Profile</h5>
                </div>
                <div class="card-body">
                    <?php if ($seller_profile): ?>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="text-muted text-uppercase small fw-bold">Store Name</label>
                                <p class="mb-0"><?php echo htmlspecialchars($seller_profile['store_name']); ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted text-uppercase small fw-bold">Commission Tier</label>
                                <p class="mb-0"><span class="badge bg-primary"><?php echo ucfirst($seller_profile['commission_tier']); ?></span></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted text-uppercase small fw-bold">Location</label>
                                <p class="mb-0"><?php echo htmlspecialchars($seller_profile['location_city'] . ', ' . $seller_profile['location_province']); ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted text-uppercase small fw-bold">Total Sales</label>
                                <p class="mb-0">R <?php echo number_format($seller_profile['total_sales_amount'], 2); ?></p>
                            </div>
                            <div class="col-12">
                                <label class="text-muted text-uppercase small fw-bold">Description</label>
                                <p class="mb-0"><?php echo nl2br(htmlspecialchars($seller_profile['store_description'])); ?></p>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">User is marked as a seller but has no profile set up.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-cart me-2"></i> Recent Orders (Buyer)</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Order #</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $orders = $pdo->prepare("SELECT order_number, created_at, total, status FROM orders WHERE buyer_id = ? ORDER BY created_at DESC LIMIT 5");
                        $orders->execute([$user['id']]);
                        $recent_orders = $orders->fetchAll();
                        foreach ($recent_orders as $o):
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($o['order_number']); ?></td>
                            <td><?php echo date('M j, Y', strtotime($o['created_at'])); ?></td>
                            <td>R <?php echo number_format($o['total'], 2); ?></td>
                            <td><?php echo ucfirst($o['status']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($recent_orders)): ?>
                        <tr><td colspan="4" class="text-center py-3 text-muted">No recent orders.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
