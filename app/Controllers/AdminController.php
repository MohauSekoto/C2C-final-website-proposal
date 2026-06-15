<?php
namespace App\Controllers;

use App\Models\User;
use App\Core\Auth;

class AdminController {
    public function loginForm() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        $error = $_SESSION['admin_error'] ?? null;
        unset($_SESSION['admin_error']);
        require_once __DIR__ . '/../Views/admin/login.php';
    }

    public function login() {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['admin_error'] = "Invalid session.";
            header("Location: /admin/login");
            exit;
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $user = User::findByEmail($email);

        if ($user && $user['role'] === 'admin' && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['name'];
            header("Location: /admin/dashboard");
            exit;
        }

        $_SESSION['admin_error'] = "Invalid credentials or unauthorized.";
        header("Location: /admin/login");
        exit;
    }

    public function index() {
        Auth::isAdmin();
        $db = new \App\Core\Database();
        
        $totalUsers = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $totalOrders = $db->query("SELECT COUNT(*) FROM orders")->fetchColumn();
        
        // Use total_amount * 0.1 as a mock revenue since commission_amount isn't in this local db yet
        $revenue = $db->query("SELECT SUM(total_amount * 0.1) FROM orders WHERE status IN ('completed', 'delivered')")->fetchColumn() ?? 0;
        $escrowVolume = $db->query("SELECT SUM(escrow_balance) FROM seller_profiles")->fetchColumn() ?? 0;
        
        $recentOrdersStmt = $db->query("
            SELECT orders.*, users.name as buyer_name, users.email as buyer_email, orders.total_amount as total
            FROM orders 
            JOIN users ON orders.user_id = users.id 
            ORDER BY orders.created_at DESC 
            LIMIT 5
        ");
        $recentOrders = $recentOrdersStmt->fetchAll();

        require_once __DIR__ . '/../Views/admin/dashboard.php';
    }

    public function users() {
        Auth::isAdmin();
        $users = User::all();
        $admin_view = __DIR__ . '/../Views/admin/users.php';
        require_once __DIR__ . '/../Views/admin/layout.php';
    }

    public function banUser() {
        Auth::isAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_POST['user_id'] ?? null;
            if ($userId) {
                $db = new \App\Core\Database();
                // Prevent admin from banning themselves
                if ($userId != $_SESSION['user_id']) {
                    $db->query("UPDATE users SET role = 'banned' WHERE id = ?", [$userId]);
                }
            }
        }
        header("Location: /admin/users");
        exit;
    }

    public function products() {
        Auth::isAdmin();
        $products = \App\Models\Product::all([]); // Fetch all without pagination limit if possible, or we just rely on existing limit. We can pass [] for filters.
        $admin_view = __DIR__ . '/../Views/admin/products.php';
        require_once __DIR__ . '/../Views/admin/layout.php';
    }

    public function deleteProduct() {
        Auth::isAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'] ?? null;
            if ($productId) {
                $db = new \App\Core\Database();
                $db->query("DELETE FROM products WHERE id = ?", [$productId]);
            }
        }
        header("Location: /admin/products");
        exit;
    }

    public function orders() {
        Auth::isAdmin();
        $db = new \App\Core\Database();
        $stmt = $db->query("
            SELECT orders.*, users.name as buyer_name, users.email as buyer_email, orders.total_amount as total
            FROM orders 
            JOIN users ON orders.user_id = users.id 
            ORDER BY orders.created_at DESC
        ");
        $orders = $stmt->fetchAll();
        $admin_view = __DIR__ . '/../Views/admin/orders.php';
        require_once __DIR__ . '/../Views/admin/layout.php';
    }

    public function updateOrder() {
        Auth::isAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = $_POST['order_id'] ?? null;
            $status = $_POST['status'] ?? null;
            if ($orderId && in_array($status, ['pending_payment', 'paid', 'processing', 'shipped', 'delivered', 'completed', 'cancelled', 'refund_requested', 'refunded'])) {
                $db = new \App\Core\Database();
                $db->query("UPDATE orders SET status = ? WHERE id = ?", [$status, $orderId]);
            }
        }
        header("Location: /admin/orders");
        exit;
    }

    public function databaseIndex() {
        Auth::isAdmin();
        $db = new \App\Core\Database();
        $tables = $db->query('SHOW TABLES')->fetchAll(\PDO::FETCH_COLUMN);
        $admin_view = __DIR__ . '/../Views/admin/database/index.php';
        require_once __DIR__ . '/../Views/admin/layout.php';
    }

    public function databaseTable($table) {
        Auth::isAdmin();
        $db = new \App\Core\Database();
        // Validate table exists
        $tables = $db->query('SHOW TABLES')->fetchAll(\PDO::FETCH_COLUMN);
        if (!in_array($table, $tables)) {
            die("Table not found.");
        }
        $columns = $db->query("DESCRIBE `$table`")->fetchAll(\PDO::FETCH_ASSOC);
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 50;
        $offset = ($page - 1) * $limit;
        
        $records = $db->query("SELECT * FROM `$table` LIMIT $limit OFFSET $offset")->fetchAll(\PDO::FETCH_ASSOC);
        $total_records = $db->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
        $total_pages = ceil($total_records / $limit);
        
        $admin_view = __DIR__ . '/../Views/admin/database/table.php';
        require_once __DIR__ . '/../Views/admin/layout.php';
    }

    public function databaseForm($table, $id = null) {
        Auth::isAdmin();
        $db = new \App\Core\Database();
        $tables = $db->query('SHOW TABLES')->fetchAll(\PDO::FETCH_COLUMN);
        if (!in_array($table, $tables)) {
            die("Table not found.");
        }
        $columns = $db->query("DESCRIBE `$table`")->fetchAll(\PDO::FETCH_ASSOC);
        
        // Find Primary Key
        $pk = 'id';
        foreach ($columns as $col) {
            if ($col['Key'] === 'PRI') {
                $pk = $col['Field'];
                break;
            }
        }

        $record = null;
        if ($id) {
            $record = $db->query("SELECT * FROM `$table` WHERE `$pk` = ?", [$id])->fetch(\PDO::FETCH_ASSOC);
        }
        
        $admin_view = __DIR__ . '/../Views/admin/database/form.php';
        require_once __DIR__ . '/../Views/admin/layout.php';
    }

    public function databaseSave($table) {
        Auth::isAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = new \App\Core\Database();
            $tables = $db->query('SHOW TABLES')->fetchAll(\PDO::FETCH_COLUMN);
            if (!in_array($table, $tables)) {
                die("Table not found.");
            }
            
            $pkField = $_POST['_pk_field'] ?? 'id';
            $pkValue = $_POST['_pk_value'] ?? null;
            $data = $_POST['data'] ?? [];
            
            if ($pkValue) {
                // Update
                $setClauses = [];
                $params = [];
                foreach ($data as $key => $value) {
                    $setClauses[] = "`$key` = ?";
                    $params[] = $value === '' ? null : $value;
                }
                $params[] = $pkValue;
                $sql = "UPDATE `$table` SET " . implode(', ', $setClauses) . " WHERE `$pkField` = ?";
                $db->query($sql, $params);
            } else {
                // Insert
                $keys = array_keys($data);
                $values = array_values($data);
                $placeholders = array_fill(0, count($values), '?');
                // replace empty strings with null
                foreach($values as &$val) { if($val === '') $val = null; }
                
                $sql = "INSERT INTO `$table` (`" . implode('`, `', $keys) . "`) VALUES (" . implode(', ', $placeholders) . ")";
                $db->query($sql, $values);
            }
        }
        header("Location: /admin/database/table/$table");
        exit;
    }

    public function databaseDelete($table) {
        Auth::isAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = new \App\Core\Database();
            $tables = $db->query('SHOW TABLES')->fetchAll(\PDO::FETCH_COLUMN);
            if (in_array($table, $tables)) {
                $pkField = $_POST['_pk_field'] ?? 'id';
                $pkValue = $_POST['_pk_value'] ?? null;
                if ($pkField && $pkValue) {
                    
                    // CASCADE DELETES LOGIC FOR USERS
                    if ($table === 'users') {
                        // Delete products created by this user
                        $db->query("DELETE FROM products WHERE seller_id = ?", [$pkValue]);
                        // Delete orders associated with this user
                        $db->query("DELETE FROM orders WHERE buyer_id = ? OR seller_id = ?", [$pkValue, $pkValue]);
                        // Delete seller profile
                        $db->query("DELETE FROM seller_profiles WHERE user_id = ?", [$pkValue]);
                        // Delete reviews
                        $db->query("DELETE FROM reviews WHERE user_id = ?", [$pkValue]);
                    }
                    
                    $db->query("DELETE FROM `$table` WHERE `$pkField` = ?", [$pkValue]);
                }
            }
        }
        header("Location: /admin/database/table/$table");
        exit;
    }
}
