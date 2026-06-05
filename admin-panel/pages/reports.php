<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';
require_admin();

$page_title = 'Platform Reports';
$pdo = get_db_connection();

// Daily revenue for the past 7 days
$revenue_query = "SELECT DATE(created_at) as date, SUM(commission_amount) as daily_revenue 
                  FROM orders 
                  WHERE status = 'completed' AND created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                  GROUP BY DATE(created_at) 
                  ORDER BY date DESC";
$revenue_data = $pdo->query($revenue_query)->fetchAll();

// Top sellers
$sellers_query = "SELECT s.store_name, s.total_sales_amount, s.commission_tier
                  FROM seller_profiles s
                  ORDER BY s.total_sales_amount DESC 
                  LIMIT 5";
$top_sellers = $pdo->query($sellers_query)->fetchAll();

include __DIR__ . '/../includes/header.php';
?>

<div class="row">
    <div class="col-md-7 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Revenue (Last 7 Days)</h5>
                <a href="#" class="btn btn-sm btn-outline-secondary"><i class="bi bi-download"></i> CSV</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0 text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Platform Commission Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($revenue_data as $row): ?>
                        <tr>
                            <td><?php echo date('M j, Y', strtotime($row['date'])); ?></td>
                            <td>R <?php echo number_format($row['daily_revenue'], 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($revenue_data)): ?>
                        <tr><td colspan="2" class="py-4 text-muted">No completed orders in the last 7 days.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-5 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0">Top Sellers (All Time)</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <?php foreach ($top_sellers as $seller): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <div>
                            <strong><?php echo htmlspecialchars($seller['store_name']); ?></strong><br>
                            <small class="badge bg-primary text-uppercase"><?php echo htmlspecialchars($seller['commission_tier']); ?></small>
                        </div>
                        <span class="fs-5 fw-bold text-success">R <?php echo number_format($seller['total_sales_amount'], 2); ?></span>
                    </li>
                    <?php endforeach; ?>
                    <?php if (empty($top_sellers)): ?>
                    <li class="list-group-item text-center py-4 text-muted">No seller data available.</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body text-center py-5">
        <i class="bi bi-file-earmark-bar-graph text-muted mb-3" style="font-size: 3rem;"></i>
        <h4>Advanced Analytics</h4>
        <p class="text-muted max-w-50 mx-auto">Full PDF and CSV export capabilities for accounting and auditing purposes will be available once the PHP exports microservice is fully deployed.</p>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
