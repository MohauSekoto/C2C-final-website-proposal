<?php
// We expect POST data from the Next.js checkout form
$amount = $_POST['amount'] ?? '0.00';
$item_name = $_POST['item_name'] ?? 'KasiBuy Order';
$m_payment_id = $_POST['m_payment_id'] ?? '';
$return_url = $_POST['return_url'] ?? 'http://localhost:3000/checkout/success';
$cancel_url = $_POST['cancel_url'] ?? 'http://localhost:3000/checkout';
$notify_url = $_POST['notify_url'] ?? 'http://localhost:8080/php-services/payments/payfast-itn.php';

// Generate a fake PayFast ID
$pf_payment_id = rand(1000000000, 9999999999);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mock PayFast Gateway</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background-color: #f4f4f5; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
        .card { background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); width: 100%; max-w: 400px; text-align: center; }
        .logo { font-size: 24px; font-weight: bold; color: #ef4444; margin-bottom: 30px; }
        .amount { font-size: 36px; font-weight: bold; color: #18181b; margin-bottom: 10px; }
        .item { color: #71717a; margin-bottom: 30px; font-size: 14px; }
        .btn { background: #18181b; color: white; border: none; padding: 15px 30px; border-radius: 10px; font-size: 16px; font-weight: 600; cursor: pointer; width: 100%; margin-bottom: 15px; transition: background 0.2s; }
        .btn:hover { background: #27272a; }
        .cancel { background: transparent; color: #71717a; padding: 10px; font-size: 14px; border: none; cursor: pointer; }
        .cancel:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="card">
    <div class="logo">Mock PayFast</div>
    <div class="amount">R <?php echo htmlspecialchars($amount); ?></div>
    <div class="item"><?php echo htmlspecialchars($item_name); ?></div>

    <form action="process.php" method="POST">
        <!-- Pass these to our processor script -->
        <input type="hidden" name="m_payment_id" value="<?php echo htmlspecialchars($m_payment_id); ?>">
        <input type="hidden" name="pf_payment_id" value="<?php echo htmlspecialchars($pf_payment_id); ?>">
        <input type="hidden" name="amount_gross" value="<?php echo htmlspecialchars($amount); ?>">
        <input type="hidden" name="return_url" value="<?php echo htmlspecialchars($return_url); ?>">
        <input type="hidden" name="notify_url" value="<?php echo htmlspecialchars($notify_url); ?>">
        
        <button type="submit" class="btn">Simulate Successful Payment</button>
    </form>
    
    <a href="<?php echo htmlspecialchars($cancel_url); ?>" class="cancel">Cancel Payment</a>
</div>

</body>
</html>
