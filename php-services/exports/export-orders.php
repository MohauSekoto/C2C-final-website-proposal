<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/database.php';

$seller_id = $_GET['seller_id'] ?? null;

if (!$seller_id) {
    die("Seller ID is required.");
}

$pdo = getDBConnection();
$stmt = $pdo->prepare("SELECT order_number, created_at, status, subtotal, shipping_cost, commission_amount, total FROM orders WHERE seller_id = ? ORDER BY created_at DESC");
$stmt->execute([$seller_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=orders_export_' . date('Y-m-d') . '.csv');

$output = fopen('php://output', 'w');
fputcsv($output, ['Order Number', 'Date', 'Status', 'Subtotal', 'Shipping Cost', 'Commission Amount', 'Total']);

foreach ($orders as $row) {
    fputcsv($output, $row);
}
fclose($output);
?>
