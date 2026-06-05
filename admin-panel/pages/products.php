<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';
require_admin();

$page_title = 'Product Moderation';
$pdo = get_db_connection();

$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

$query = "SELECT p.*, u.name as seller_name, c.name as category_name 
          FROM products p
          JOIN users u ON p.seller_id = u.id
          LEFT JOIN categories c ON p.category_id = c.id";

$params = [];
if ($status_filter) {
    $query .= " WHERE p.status = ?";
    $params[] = $status_filter;
}
$query .= " ORDER BY p.created_at DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll();

include __DIR__ . '/../includes/header.php';
?>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">All Products</h5>
        <form method="GET" class="d-flex">
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                <option value="active" <?php echo $status_filter === 'active' ? 'selected' : ''; ?>>Active</option>
                <option value="draft" <?php echo $status_filter === 'draft' ? 'selected' : ''; ?>>Draft</option>
                <option value="paused" <?php echo $status_filter === 'paused' ? 'selected' : ''; ?>>Paused</option>
                <option value="removed" <?php echo $status_filter === 'removed' ? 'selected' : ''; ?>>Removed</option>
            </select>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">Img</th>
                        <th>Title</th>
                        <th>Seller</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): 
                        $images = json_decode($product['images'], true);
                        $img_url = !empty($images) ? $images[0] : 'https://placehold.co/100x100?text=No+Img';
                    ?>
                    <tr>
                        <td><img src="<?php echo htmlspecialchars($img_url); ?>" class="rounded" width="40" height="40" style="object-fit: cover;"></td>
                        <td>
                            <strong><?php echo htmlspecialchars($product['title']); ?></strong><br>
                            <small class="text-muted"><?php echo htmlspecialchars($product['category_name']); ?></small>
                        </td>
                        <td><a href="user-detail.php?id=<?php echo urlencode($product['seller_id']); ?>"><?php echo htmlspecialchars($product['seller_name']); ?></a></td>
                        <td>R <?php echo number_format($product['price'], 2); ?></td>
                        <td>
                            <?php
                            $badge = match($product['status']) {
                                'active' => 'success',
                                'draft' => 'secondary',
                                'removed' => 'danger',
                                'paused' => 'warning text-dark',
                                'sold_out' => 'dark',
                                default => 'secondary'
                            };
                            ?>
                            <span class="badge bg-<?php echo $badge; ?>"><?php echo ucfirst(str_replace('_', ' ', $product['status'])); ?></span>
                        </td>
                        <td>
                            <a href="product-detail.php?id=<?php echo urlencode($product['id']); ?>" class="btn btn-sm btn-outline-primary">Review</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($products)): ?>
                    <tr><td colspan="6" class="text-center py-4 text-muted">No products found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
