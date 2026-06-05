<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';
require_admin();

$page_title = 'Seller Management';
$pdo = get_db_connection();

$query = "SELECT sp.*, u.name as owner_name, u.email 
          FROM seller_profiles sp 
          JOIN users u ON sp.user_id = u.id 
          ORDER BY sp.created_at DESC";
$stmt = $pdo->query($query);
$sellers = $stmt->fetchAll();

include __DIR__ . '/../includes/header.php';
?>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">Sellers List</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Store Name</th>
                        <th>Owner</th>
                        <th>Tier</th>
                        <th>Total Sales</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sellers as $seller): ?>
                    <tr>
                        <td>
                            <strong><?php echo htmlspecialchars($seller['store_name']); ?></strong><br>
                            <small class="text-muted"><?php echo htmlspecialchars($seller['location_city'] . ', ' . $seller['location_province']); ?></small>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($seller['owner_name']); ?><br>
                            <small class="text-muted"><?php echo htmlspecialchars($seller['email']); ?></small>
                        </td>
                        <td>
                            <span class="badge bg-primary text-uppercase"><?php echo htmlspecialchars($seller['commission_tier']); ?></span>
                        </td>
                        <td>R <?php echo number_format($seller['total_sales_amount'], 2); ?></td>
                        <td>
                            <?php if ($seller['is_verified']): ?>
                                <span class="badge bg-success"><i class="bi bi-check-circle"></i> Verified</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split"></i> Pending</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="user-detail.php?id=<?php echo urlencode($seller['user_id']); ?>" class="btn btn-sm btn-outline-primary">View Owner</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($sellers)): ?>
                    <tr><td colspan="6" class="text-center py-4 text-muted">No sellers found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
