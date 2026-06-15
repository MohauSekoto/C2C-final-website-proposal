<?php
namespace App\Models;

use App\Core\Database;

class Order {
    public static function findByBuyer($user_id) {
        $db = new Database();
        $stmt = $db->query("
            SELECT o.*, 
                   (SELECT COUNT(*) FROM order_items WHERE order_id = o.id) as item_count 
            FROM orders o 
            WHERE o.user_id = ? 
            ORDER BY o.created_at DESC
        ", [$user_id]);
        return $stmt->fetchAll();
    }

    public static function getItems($order_id) {
        $db = new Database();
        $stmt = $db->query("
            SELECT oi.*, p.title, p.image_url 
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = ?
        ", [$order_id]);
        return $stmt->fetchAll();
    }

    public static function findBySeller($seller_id) {
        $db = new Database();
        $stmt = $db->query("
            SELECT DISTINCT o.*, u.name as buyer_name, u.email as buyer_email, u.address as buyer_address
            FROM orders o
            JOIN order_items oi ON o.id = oi.order_id
            JOIN products p ON oi.product_id = p.id
            JOIN users u ON o.user_id = u.id
            WHERE p.seller_id = ?
            ORDER BY o.created_at DESC
        ", [$seller_id]);
        return $stmt->fetchAll();
    }

    public static function updateStatus($order_id, $status) {
        $db = new Database();
        $db->query("UPDATE orders SET status = ? WHERE id = ?", [$status, $order_id]);
    }
}
