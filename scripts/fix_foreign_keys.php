<?php
require_once __DIR__ . '/../app/Core/Database.php';
$db = new App\Core\Database();

// Alter order_items
$db->query('ALTER TABLE order_items DROP FOREIGN KEY order_items_ibfk_2;');
$db->query('ALTER TABLE order_items MODIFY product_id INT NULL;');
$db->query('ALTER TABLE order_items ADD CONSTRAINT order_items_ibfk_2 FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL;');

// Alter wishlists (just in case)
// Wait, wishlists already has ON DELETE CASCADE: FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE

// Alter reviews (just in case)
// Reviews already has ON DELETE CASCADE.

echo "Foreign key updated successfully!\n";
