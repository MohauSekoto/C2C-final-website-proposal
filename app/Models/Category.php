<?php
namespace App\Models;

use App\Core\Database;

class Category {
    public static function all() {
        $db = new Database();
        $stmt = $db->query("SELECT id, name, slug FROM categories ORDER BY id ASC");
        return $stmt->fetchAll();
    }

    public static function findBySlug($slug) {
        $db = new Database();
        $stmt = $db->query("SELECT * FROM categories WHERE slug = ?", [$slug]);
        return $stmt->fetch();
    }
}
