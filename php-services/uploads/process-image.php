<?php
declare(strict_types=1);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit(json_encode(['error' => 'Method not allowed']));
}

if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    exit(json_encode(['error' => 'No image uploaded or upload error occurred']));
}

$file = $_FILES['image'];
$allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];

if (!in_array($file['type'], $allowedTypes)) {
    http_response_code(400);
    exit(json_encode(['error' => 'Invalid file type. Only JPG, PNG, and WEBP are allowed.']));
}

// In a real app, this would upload to S3 or a dedicated media server
// For this scaffolding, we just simulate a successful upload returning a URL
$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = uniqid('img_', true) . '.' . $ext;
$mockUrl = 'https://example.com/uploads/' . $filename;

echo json_encode([
    'success' => true,
    'url' => $mockUrl,
    'filename' => $filename
]);
?>
