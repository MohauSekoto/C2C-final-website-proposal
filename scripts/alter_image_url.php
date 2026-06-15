<?php
require_once __DIR__ . '/../app/Core/Database.php';

$db = new \App\Core\Database();
$pdo = $db->getConnection();

try {
    $pdo->exec("ALTER TABLE products MODIFY image_url LONGTEXT;");
    echo "Successfully altered image_url to LONGTEXT.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
