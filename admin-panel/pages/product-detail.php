<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';
require_admin();

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: products.php");
    exit;
}

$pdo = get_db_connection();
$stmt = $pdo->prepare("SELECT p.*, u.name as seller_name, c.name as category_name 
                       FROM products p 
                       JOIN users u ON p.seller_id = u.id 
                       LEFT JOIN categories c ON p.category_id = c.id 
                       WHERE p.id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    die("Product not found.");
}

$page_title = "Product Moderation: " . htmlspecialchars($product['title']);
include __DIR__ . '/../includes/header.php';

$images = json_decode($product['images'], true) ?? [];
?>

<div class="row">
    <div class="col-md-5 mb-4">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner rounded">
                        <?php if (empty($images)): ?>
                            <div class="carousel-item active">
                                <img src="https://placehold.co/600x600?text=No+Image" class="d-block w-100" alt="No Image">
                            </div>
                        <?php else: ?>
                            <?php foreach ($images as $index => $img): ?>
                                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                    <img src="<?php echo htmlspecialchars($img); ?>" class="d-block w-100" alt="Product Image" style="object-fit: cover; aspect-ratio: 1/1;">
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <?php if (count($images) > 1): ?>
                    <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0">Moderation Actions</h5>
            </div>
            <div class="card-body">
                <p>Current Status: 
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
                    <span class="badge bg-<?php echo $badge; ?>"><?php echo ucfirst($product['status']); ?></span>
                </p>
                <form action="../actions/product-action.php" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
                    <div class="d-grid gap-2">
                        <?php if ($product['status'] !== 'active'): ?>
                            <button type="submit" name="action" value="approve" class="btn btn-success"><i class="bi bi-check-circle"></i> Approve & Set Active</button>
                        <?php endif; ?>
                        <?php if ($product['status'] !== 'removed'): ?>
                            <button type="submit" name="action" value="remove" class="btn btn-danger" onclick="return confirm('Are you sure you want to remove this product?');"><i class="bi bi-trash"></i> Remove Product</button>
                        <?php endif; ?>
                        <?php if ($product['status'] === 'active'): ?>
                            <button type="submit" name="action" value="pause" class="btn btn-warning"><i class="bi bi-pause-circle"></i> Pause Listing</button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-7 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <h3 class="mb-3"><?php echo htmlspecialchars($product['title']); ?></h3>
                <h4 class="text-primary mb-4">R <?php echo number_format($product['price'], 2); ?></h4>
                
                <div class="row mb-4">
                    <div class="col-sm-6 mb-3">
                        <label class="text-muted text-uppercase small fw-bold">Seller</label>
                        <p class="mb-0"><a href="user-detail.php?id=<?php echo urlencode($product['seller_id']); ?>"><?php echo htmlspecialchars($product['seller_name']); ?></a></p>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label class="text-muted text-uppercase small fw-bold">Category</label>
                        <p class="mb-0"><?php echo htmlspecialchars($product['category_name'] ?? 'Uncategorized'); ?></p>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label class="text-muted text-uppercase small fw-bold">Stock Quantity</label>
                        <p class="mb-0"><?php echo (int)$product['stock_quantity']; ?> units</p>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label class="text-muted text-uppercase small fw-bold">Weight / Dims</label>
                        <p class="mb-0">
                            <?php echo $product['weight_kg'] ? $product['weight_kg'].' kg' : 'N/A'; ?>
                            <?php 
                            $dims = json_decode($product['dimensions_cm'], true);
                            if ($dims && isset($dims['l'], $dims['w'], $dims['h'])) {
                                echo " | {$dims['l']}x{$dims['w']}x{$dims['h']} cm";
                            }
                            ?>
                        </p>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label class="text-muted text-uppercase small fw-bold">Created At</label>
                        <p class="mb-0"><?php echo date('M j, Y g:i a', strtotime($product['created_at'])); ?></p>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label class="text-muted text-uppercase small fw-bold">Average Rating</label>
                        <p class="mb-0"><?php echo $product['avg_rating']; ?> <i class="bi bi-star-fill text-warning"></i> (<?php echo $product['review_count']; ?> reviews)</p>
                    </div>
                </div>

                <label class="text-muted text-uppercase small fw-bold">Description</label>
                <div class="p-3 bg-light rounded mt-1">
                    <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
