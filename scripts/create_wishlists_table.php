<?php
require_once __DIR__ . '/../app/Core/Database.php';

$db = new \App\Core\Database();
$pdo = $db->getConnection();

try {
    $pdo->exec("
    CREATE TABLE IF NOT EXISTS wishlists (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        product_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
        UNIQUE KEY user_product (user_id, product_id)
    );
    ");
    echo "Successfully created wishlists table.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
