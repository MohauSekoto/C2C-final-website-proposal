<?php
namespace App\Models;

use App\Core\Database;

class Product {
    public static function all($filters = []) {
        $db = new Database();
        $query = "SELECT products.*, categories.name as category_name FROM products JOIN categories ON products.category_id = categories.id WHERE 1=1";
        $params = [];

        if (!empty($filters['categories'])) {
            $placeholders = str_repeat('?,', count($filters['categories']) - 1) . '?';
            $query .= " AND categories.name IN ($placeholders)";
            $params = array_merge($params, $filters['categories']);
        }

        if (!empty($filters['min_price'])) {
            $query .= " AND products.price >= ?";
            $params[] = $filters['min_price'];
        }

        if (!empty($filters['max_price'])) {
            $query .= " AND products.price <= ?";
            $params[] = $filters['max_price'];
        }

        if (!empty($filters['search'])) {
            $query .= " AND products.title LIKE ?";
            $params[] = '%' . $filters['search'] . '%';
        }

        $sort = $filters['sort'] ?? 'newest';
        if ($sort === 'price_asc') {
            $query .= " ORDER BY products.price ASC";
        } elseif ($sort === 'price_desc') {
            $query .= " ORDER BY products.price DESC";
        } else {
            $query .= " ORDER BY products.created_at DESC";
        }

        $stmt = $db->query($query, $params);
        return $stmt->fetchAll();
    }

    public static function find($id) {
        $db = new Database();
        $stmt = $db->query("
            SELECT products.*, categories.name as category_name, 
                   COALESCE(seller_profiles.store_name, users.name) as seller_name,
                   (SELECT AVG(rating) FROM reviews WHERE product_id = products.id) as avg_rating,
                   (SELECT COUNT(*) FROM reviews WHERE product_id = products.id) as review_count
            FROM products 
            JOIN categories ON products.category_id = categories.id 
            JOIN users ON products.seller_id = users.id 
            LEFT JOIN seller_profiles ON users.id = seller_profiles.user_id
            WHERE products.id = ?
        ", [$id]);
        return $stmt->fetch();
    }

    public static function getReviews($id) {
        $db = new Database();
        $stmt = $db->query("
            SELECT reviews.*, users.name as reviewer_name 
            FROM reviews 
            JOIN users ON reviews.user_id = users.id 
            WHERE reviews.product_id = ? 
            ORDER BY reviews.created_at DESC
        ", [$id]);
        return $stmt->fetchAll();
    }

    public static function findBySeller($seller_id) {
        $db = new Database();
        $stmt = $db->query("SELECT products.*, categories.name as category_name FROM products JOIN categories ON products.category_id = categories.id WHERE products.seller_id = ? ORDER BY products.created_at DESC", [$seller_id]);
        return $stmt->fetchAll();
    }

    public static function create($data) {
        $db = new Database();
        $query = "INSERT INTO products (category_id, seller_id, title, description, price, stock_quantity, image_url, is_on_sale) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $params = [
            $data['category_id'],
            $data['seller_id'],
            $data['title'],
            $data['description'],
            $data['price'],
            $data['stock_quantity'],
            $data['image_url'],
            $data['is_on_sale'] ?? 0
        ];
        $db->query($query, $params);
        return $db->getConnection()->lastInsertId();
    }

    public static function update($id, $seller_id, $data) {
        $db = new Database();
        $query = "UPDATE products SET category_id = ?, title = ?, description = ?, price = ?, stock_quantity = ?, is_on_sale = ?";
        $params = [
            $data['category_id'],
            $data['title'],
            $data['description'],
            $data['price'],
            $data['stock_quantity'],
            $data['is_on_sale'] ?? 0
        ];
        
        if (!empty($data['image_url'])) {
            $query .= ", image_url = ?";
            $params[] = $data['image_url'];
        }
        
        $query .= " WHERE id = ? AND seller_id = ?";
        $params[] = $id;
        $params[] = $seller_id;
        
        $db->query($query, $params);
        return true;
    }
}
