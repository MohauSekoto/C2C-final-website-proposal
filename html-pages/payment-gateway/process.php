<?php
// Mock PayFast Processing Script
$m_payment_id = $_POST['m_payment_id'] ?? '';
$pf_payment_id = $_POST['pf_payment_id'] ?? '';
$amount_gross = $_POST['amount_gross'] ?? '0.00';
$return_url = $_POST['return_url'] ?? 'http://localhost:3000';
$notify_url = $_POST['notify_url'] ?? '';

if ($notify_url) {
    // Build ITN Payload
    $itn_data = [
        'm_payment_id' => $m_payment_id,
        'pf_payment_id' => $pf_payment_id,
        'payment_status' => 'COMPLETE',
        'item_name' => 'KasiBuy Order (Mock)',
        'item_description' => '',
        'amount_gross' => $amount_gross,
        'amount_fee' => '0.00',
        'amount_net' => $amount_gross,
        'custom_str1' => '',
        'custom_str2' => '',
        'custom_str3' => '',
        'custom_str4' => '',
        'custom_str5' => '',
        'custom_int1' => '',
        'custom_int2' => '',
        'custom_int3' => '',
        'custom_int4' => '',
        'custom_int5' => '',
        'name_first' => 'Mock',
        'name_last' => 'Buyer',
        'email_address' => 'mock@buyer.com',
        'merchant_id' => '10000100',
        'signature' => 'mock_signature'
    ];

    // Send the ITN to the backend via cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $notify_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($itn_data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5); // 5 seconds timeout
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    // In a real environment, PayFast logs the $http_code. We just ignore it for the mock.
}

// Redirect user to success page
header("Location: " . $return_url);
exit();
?>
