<?php
require_once 'c:\Users\Makro\Documents\ITECA3-12\Final Website\Gemini version clone\app\Core\Database.php';
require_once 'c:\Users\Makro\Documents\ITECA3-12\Final Website\Gemini version clone\app\Models\User.php';

use App\Models\User;

try {
    $existing = User::findByEmail('admin@kasibuy.co.za');
    if ($existing) {
        $db = new \App\Core\Database();
        $hash = password_hash('admin123', PASSWORD_DEFAULT);
        $db->query("UPDATE users SET password_hash = ? WHERE email = ?", [$hash, 'admin@kasibuy.co.za']);
        echo "Admin password reset successfully!\nEmail: admin@kasibuy.co.za\nPassword: admin123\nRole: admin";
    } else {
        $id = User::create('System Admin', 'admin@kasibuy.co.za', 'admin123', 'admin');
        echo "Admin created successfully!\nEmail: admin@kasibuy.co.za\nPassword: admin123\nRole: admin";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
