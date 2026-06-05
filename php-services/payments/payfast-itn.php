<?php
// Must return 200 OK immediately
header("HTTP/1.1 200 OK");

require_once __DIR__ . '/../config/database.php';

$logFile = __DIR__ . '/itn.log';
file_put_contents($logFile, date('[Y-m-d H:i:s] ') . "ITN Webhook Triggered\n", FILE_APPEND);
file_put_contents($logFile, print_r($_POST, true) . "\n", FILE_APPEND);

$m_payment_id = $_POST['m_payment_id'] ?? null; // Our Order ID
$pf_payment_id = $_POST['pf_payment_id'] ?? null; // PayFast Reference
$payment_status = $_POST['payment_status'] ?? null;

if ($payment_status === 'COMPLETE' && $m_payment_id) {
    try {
        $db = getDBConnection();
        $stmt = $db->prepare("UPDATE orders SET status = 'paid', payment_reference = ?, paid_at = NOW() WHERE id = ?");
        $stmt->execute([$pf_payment_id, $m_payment_id]);
        
        file_put_contents($logFile, date('[Y-m-d H:i:s] ') . "SUCCESS: Order $m_payment_id marked as PAID in database.\n", FILE_APPEND);
    } catch (Exception $e) {
        file_put_contents($logFile, date('[Y-m-d H:i:s] ') . "DB ERROR: " . $e->getMessage() . "\n", FILE_APPEND);
    }
} else {
    file_put_contents($logFile, date('[Y-m-d H:i:s] ') . "IGNORED: Payment status is $payment_status.\n", FILE_APPEND);
}
?>
