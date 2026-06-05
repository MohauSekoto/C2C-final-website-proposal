<?php
declare(strict_types=1);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit(json_encode(['error' => 'Method not allowed']));
}

$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

$name = filter_var($input['name'] ?? '', FILTER_SANITIZE_STRING);
$email = filter_var($input['email'] ?? '', FILTER_SANITIZE_EMAIL);
$message = filter_var($input['message'] ?? '', FILTER_SANITIZE_STRING);

if (!$name || !$email || !$message) {
    http_response_code(400);
    exit(json_encode(['error' => 'Name, email, and message are required.']));
}

// Mock sending email
// mail('support@kasibuy.co.za', "Contact Form: $name", $message, "From: $email");

echo json_encode([
    'success' => true,
    'message' => 'Your message has been sent to our support team. We will get back to you shortly.'
]);
?>
