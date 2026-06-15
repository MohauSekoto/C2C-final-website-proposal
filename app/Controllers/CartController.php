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

        $shipping_cost = (float)($_POST['shipping_cost'] ?? 150.00);
        $shipping_method = $_POST['shipping_method'] ?? 'economy';
        $commission_fee = $subtotal * 0.05; // 5% platform fee
        $total_amount = $subtotal + $shipping_cost + $commission_fee;

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
            
            // Generate PayFast Sandbox Auto-Submit Form
            $merchant_id = '10000100'; // PayFast Sandbox Merchant ID
            $merchant_key = '46f0cd694581a'; // PayFast Sandbox Merchant Key
            $return_url = "https://" . $_SERVER['HTTP_HOST'] . "/checkout/success?order_id=" . $order_id;
            $cancel_url = "https://" . $_SERVER['HTTP_HOST'] . "/cart";
            
            echo '<!DOCTYPE html><html><head><title>Redirecting to PayFast...</title></head><body style="display:flex; justify-content:center; align-items:center; height:100vh; font-family:sans-serif; background:#f8fafc;">';
            echo '<div style="text-align:center;">';
            echo '<h2>Securely redirecting to PayFast...</h2>';
            echo '<form action="https://sandbox.payfast.co.za/eng/process" method="POST" id="payfast-form">';
            echo '<input type="hidden" name="merchant_id" value="' . $merchant_id . '">';
            echo '<input type="hidden" name="merchant_key" value="' . $merchant_key . '">';
            echo '<input type="hidden" name="return_url" value="' . $return_url . '">';
            echo '<input type="hidden" name="cancel_url" value="' . $cancel_url . '">';
            echo '<input type="hidden" name="amount" value="' . number_format($total_amount, 2, '.', '') . '">';
            echo '<input type="hidden" name="item_name" value="KasiBuy Order #' . $order_id . '">';
            echo '<input type="submit" value="Click here if you are not redirected automatically" style="margin-top: 20px; padding: 10px 20px; background: #eab308; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">';
            echo '</form>';
            echo '<script>document.getElementById("payfast-form").submit();</script>';
            echo '</div></body></html>';
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
