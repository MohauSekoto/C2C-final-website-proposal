<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/database.php';

$order_id = $_GET['order_id'] ?? null;

if (!$order_id) {
    die("Order ID is required.");
}

// In a real application, this service would use a library like TCPDF or Dompdf to convert HTML to PDF.
// For this scaffolding, we redirect to the HTML invoice template and pass the order_id.
header("Location: /html-pages/print/invoice.html?order_id=" . urlencode($order_id));
exit;
?>
