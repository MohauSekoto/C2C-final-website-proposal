<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Models\User;

class ProfileController {
    public function index() {
        Auth::check();
        $user_id = $_SESSION['user_id'];
        $user = User::findById($user_id);
        
        $orders = \App\Models\Order::findByBuyer($user_id);
        foreach ($orders as &$order) {
            $order['items'] = \App\Models\Order::getItems($order['id']);
        }
        $wishlist = \App\Models\Wishlist::findByUser($user_id);
        
        $success = $_SESSION['success'] ?? null;
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['success'], $_SESSION['error']);
        
        require_once __DIR__ . '/../Views/profile.php';
    }

    public function update() {
        Auth::check();
        $user_id = $_SESSION['user_id'];
        
        $name = $_POST['name'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $address = $_POST['address'] ?? '';
        
        if (empty($name)) {
            $_SESSION['error'] = "Name cannot be empty.";
        } else {
            try {
                User::updateProfile($user_id, $name, $phone, $address);
                $_SESSION['name'] = $name;
                $_SESSION['success'] = "Profile updated successfully.";
            } catch (\Exception $e) {
                $_SESSION['error'] = "Error updating profile: " . $e->getMessage();
            }
        }
        
        header("Location: /profile");
        exit;
    }

    public function toggleWishlist() {
        Auth::check();
        $user_id = $_SESSION['user_id'];
        $product_id = $_POST['product_id'] ?? null;
        $redirect = $_POST['redirect'] ?? '/products';

        if ($product_id) {
            if (\App\Models\Wishlist::hasProduct($user_id, $product_id)) {
                \App\Models\Wishlist::remove($user_id, $product_id);
            } else {
                \App\Models\Wishlist::add($user_id, $product_id);
            }
        }
        
        header("Location: " . $redirect);
        exit;
    }

    public function confirmReceipt() {
        Auth::check();
        $user_id = $_SESSION['user_id'];
        $order_id = $_POST['order_id'] ?? null;

        if ($order_id) {
            $db = new \App\Core\Database();
            $stmt = $db->query("SELECT id FROM orders WHERE id = ? AND user_id = ?", [$order_id, $user_id]);
            if ($stmt->fetch()) {
                \App\Models\Order::updateStatus($order_id, 'delivered');
                
                // Calculate payout per seller
                $items = \App\Models\Order::getItems($order_id);
                $sellerPayouts = [];
                foreach ($items as $item) {
                    $pStmt = $db->query("SELECT seller_id FROM products WHERE id = ?", [$item['product_id']]);
                    $product = $pStmt->fetch();
                    if ($product) {
                        $seller_id = $product['seller_id'];
                        if (!isset($sellerPayouts[$seller_id])) {
                            $sellerPayouts[$seller_id] = 0;
                        }
                        $sellerPayouts[$seller_id] += ($item['price'] * $item['quantity']);
                    }
                }
                
                // Update escrow balance
                foreach ($sellerPayouts as $s_id => $amount) {
                    // Assuming shipping fee goes to platform for simplicity or we just pay out subtotal
                    $db->query("UPDATE seller_profiles SET escrow_balance = escrow_balance + ? WHERE user_id = ?", [$amount, $s_id]);
                }
                
                $_SESSION['success'] = "Thank you for confirming receipt. The seller has been paid from escrow.";
            }
        }
        
        header("Location: /profile?tab=orders");
        exit;
    }
}
