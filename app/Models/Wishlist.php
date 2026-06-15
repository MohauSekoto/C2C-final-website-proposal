<?php
namespace App\Models;

use App\Core\Database;

class Wishlist {
    public static function findByUser($user_id) {
        $db = new Database();
        $stmt = $db->query("
            SELECT w.*, p.title, p.price, p.image_url, p.is_on_sale, p.stock_quantity, c.name as category_name
            FROM wishlists w 
            JOIN products p ON w.product_id = p.id 
            JOIN categories c ON p.category_id = c.id
            WHERE w.user_id = ? 
            ORDER BY w.created_at DESC
        ", [$user_id]);
        return $stmt->fetchAll();
    }

    public static function add($user_id, $product_id) {
        $db = new Database();
        try {
            $db->query("INSERT IGNORE INTO wishlists (user_id, product_id) VALUES (?, ?)", [$user_id, $product_id]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function remove($user_id, $product_id) {
        $db = new Database();
        $db->query("DELETE FROM wishlists WHERE user_id = ? AND product_id = ?", [$user_id, $product_id]);
        return true;
    }

    public static function hasProduct($user_id, $product_id) {
        $db = new Database();
        $stmt = $db->query("SELECT 1 FROM wishlists WHERE user_id = ? AND product_id = ?", [$user_id, $product_id]);
        return (bool) $stmt->fetch();
    }
}
