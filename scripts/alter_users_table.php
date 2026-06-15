<?php
require_once __DIR__ . '/../app/Core/Database.php';

$db = new \App\Core\Database();
$pdo = $db->getConnection();

try {
    $pdo->exec("ALTER TABLE users ADD COLUMN phone VARCHAR(50) DEFAULT NULL;");
    echo "Added phone column.\n";
} catch (Exception $e) {
    echo "Phone column might already exist: " . $e->getMessage() . "\n";
}

try {
    $pdo->exec("ALTER TABLE users ADD COLUMN address TEXT DEFAULT NULL;");
    echo "Added address column.\n";
} catch (Exception $e) {
    echo "Address column might already exist: " . $e->getMessage() . "\n";
}
