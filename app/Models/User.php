<?php
namespace App\Models;

use App\Core\Database;

class User {
    public static function findByEmail($email) {
        $db = new Database();
        $stmt = $db->query("SELECT * FROM users WHERE email = ?", [$email]);
        return $stmt->fetch();
    }

    public static function findById($id) {
        $db = new Database();
        $stmt = $db->query("SELECT * FROM users WHERE id = ?", [$id]);
        return $stmt->fetch();
    }

    public static function all() {
        $db = new Database();
        $stmt = $db->query("SELECT * FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public static function create($name, $email, $password, $role = 'buyer') {
        $db = new Database();
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        $db->query("INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)", [$name, $email, $hash, $role]);
        return $db->getConnection()->lastInsertId();
    }

    public static function updateProfile($id, $name, $phone, $address) {
        $db = new Database();
        $db->query("UPDATE users SET name = ?, phone = ?, address = ? WHERE id = ?", [$name, $phone, $address, $id]);
        return true;
    }
}
