<?php
namespace App\Controllers;

class CartController {
    public function __construct() {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    public function index() {
        $cart = $_SESSION['cart'];
        require_once __DIR__ . '/../Views/cart.php';
    }

    public function add() {
        $id = $_POST['product_id'] ?? null;
        $qty = (int)($_POST['quantity'] ?? 1);
        
        if ($id) {
            if (isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id]['quantity'] += $qty;
            } else {
                $_SESSION['cart'][$id] = [
                    'product_id' => $id,
                    'quantity' => $qty,
                    'title' => $_POST['title'] ?? 'Product',
                    'price' => $_POST['price'] ?? 0
                ];
            }
        }
        header("Location: /cart");
    }

    public function update() {
        $id = $_POST['product_id'] ?? null;
        $qty = (int)($_POST['quantity'] ?? 1);
        if ($id && isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity'] = max(1, $qty);
        }
        header("Location: /cart");
    }

    public function remove() {
        $id = $_POST['product_id'] ?? null;
        if ($id) {
            unset($_SESSION['cart'][$id]);
        }
        header("Location: /cart");
    }

    public function checkout() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['redirect_url'] = '/checkout';
            header("Location: /login");
            exit;
        }
        $user = \App\Models\User::findById($_SESSION['user_id']);
        $cart = $_SESSION['cart'] ?? [];
        
        if (empty($cart)) {
            header("Location: /cart");
            exit;
        }

        require_once __DIR__ . '/../Views/checkout.php';
    }

    public function processCheckout() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }

        $user_id = $_SESSION['user_id'];
        $cart = $_SESSION['cart'] ?? [];
        
        if (empty($cart)) {
            header("Location: /cart");
            exit;
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $shipping_fee = 150.00;
        $commission_fee = $subtotal * 0.05; // 5% platform fee
        $total_amount = $subtotal + $shipping_fee + $commission_fee;

        try {
            $db = new \App\Core\Database();
            $db->query("INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, 'pending')", [$user_id, $total_amount]);
            $order_id = $db->getConnection()->lastInsertId();

            foreach ($cart as $item) {
                $db->query("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)", [
                    $order_id,
                    $item['product_id'],
                    $item['quantity'],
                    $item['price']
                ]);
                
                // Reduce stock quantity
                $db->query("UPDATE products SET stock_quantity = GREATEST(0, stock_quantity - ?) WHERE id = ?", [
                    $item['quantity'],
                    $item['product_id']
                ]);
            }

            $_SESSION['cart'] = [];
            header("Location: /checkout/success?order_id=" . $order_id);
            exit;
        } catch (\Exception $e) {
            error_log("Checkout Error: " . $e->getMessage());
            header("Location: /checkout?error=processing_failed");
            exit;
        }
    }
    
    public function success() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }
        $order_id = $_GET['order_id'] ?? null;
        require_once __DIR__ . '/../Views/checkout_success.php';
    }
}
