<?php
namespace App\Core;

class Auth {
    public static function check() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
            header("Location: /login");
            exit;
        }
        return true;
    }

    public static function isSellerOrAdmin() {
        self::check();
        $role = $_SESSION['role'] ?? 'buyer';
        if ($role !== 'seller' && $role !== 'admin') {
            header("Location: /register-store");
            exit;
        }
        return true;
    }

    public static function isAdmin() {
        self::check();
        if (($_SESSION['role'] ?? '') !== 'admin') {
            // Kick them out if not admin
            header("Location: /");
            exit;
        }
        return true;
    }
}
