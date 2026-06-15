<?php
require_once 'c:\Users\Makro\Documents\ITECA3-12\Final Website\Gemini version clone\app\Core\Database.php';

use App\Core\Database;

try {
    $db = new Database();
    $conn = $db->getConnection();
    $conn->exec("ALTER TABLE users MODIFY role ENUM('buyer', 'seller', 'admin', 'banned') DEFAULT 'buyer'");
    echo "Database updated successfully: role ENUM includes 'banned'.\n";
} catch (Exception $e) {
    echo "Error updating database: " . $e->getMessage() . "\n";
}
