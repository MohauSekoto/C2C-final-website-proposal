<?php
// app/Controllers/AuthController.php
namespace App\Controllers;

use App\Core\Database;
use App\Models\User;

class AuthController {
    public function loginForm() {
        // Generate CSRF token if not exists
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        $title = "KasiBuy - Login";
        $view = __DIR__ . '/../Views/login.php';
        
        // Pass any errors from session to view
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']);
        
        require_once __DIR__ . '/../Views/layout.php';
    }

    public function login() {
        // Check CSRF token
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = "Invalid CSRF token. Please try again.";
            header("Location: /login");
            exit;
        }

        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';

        if (!$email || !$password) {
            $_SESSION['error'] = "Please provide both email and password.";
            header("Location: /login");
            exit;
        }

        try {
            $user = User::findByEmail($email);

            if ($user && password_verify($password, $user['password_hash'])) {
                // Success
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role']; // 'buyer', 'seller', or 'admin'
                $_SESSION['name'] = $user['name'];

                $redirect = $_SESSION['redirect_url'] ?? '/';
                unset($_SESSION['redirect_url']);
                header("Location: " . $redirect);
                exit;
            } else {
                $_SESSION['error'] = "Invalid email or password.";
                header("Location: /login");
                exit;
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = "Database error: " . $e->getMessage();
            header("Location: /login");
            exit;
        }
    }

    public function registerForm() {
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']);
        $title = "KasiBuy - Create Account";
        require_once __DIR__ . '/../Views/register.php';
    }

    public function register() {
        // Mock registration logic
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        try {
            $userId = User::create($name, $email, $password, 'buyer');
            $_SESSION['user_id'] = $userId;
            $_SESSION['role'] = 'buyer';
            $_SESSION['name'] = $name;
            $_SESSION['success'] = "Account successfully created!";
            header("Location: /");
        } catch (\Exception $e) {
            $_SESSION['error'] = "Could not register: " . $e->getMessage();
            header("Location: /register");
        }
    }

    public function registerStoreForm() {
        require_once __DIR__ . '/../Views/register_store.php';
    }

    public function registerStore() {
        // Upgrade user to seller
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }
        
        $user_id = $_SESSION['user_id'];
        $store_name = $_POST['store_name'] ?? 'My Store';
        $location = $_POST['location'] ?? '';
        $store_description = $_POST['store_description'] ?? '';
        
        try {
            $db = new Database();
            // Check if seller profile already exists
            $stmt = $db->query("SELECT id FROM seller_profiles WHERE user_id = ?", [$user_id]);
            if (!$stmt->fetch()) {
                $db->query("INSERT INTO seller_profiles (user_id, store_name, store_description, location) VALUES (?, ?, ?, ?)", [
                    $user_id,
                    $store_name,
                    $store_description,
                    $location
                ]);
            }
            
            $db->query("UPDATE users SET role = 'seller' WHERE id = ?", [$user_id]);
            $_SESSION['role'] = 'seller';
            header("Location: /dashboard");
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function logout() {
        session_destroy();
        header("Location: /");
        exit;
    }
}
