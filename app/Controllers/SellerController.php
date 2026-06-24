<?php
namespace App\Controllers;

use App\Core\Auth;

class SellerController {
    public function index() {
        Auth::isSellerOrAdmin();
        $seller_id = $_SESSION['user_id'];
        
        $products = \App\Models\Product::findBySeller($seller_id);
        $orders = \App\Models\Order::findBySeller($seller_id);
        
        $db = new \App\Core\Database();
        
        // Calculate Total Sales
        $stmt = $db->query("SELECT SUM(oi.price * oi.quantity) as total_sales FROM order_items oi JOIN products p ON oi.product_id = p.id JOIN orders o ON oi.order_id = o.id WHERE p.seller_id = ? AND o.status = 'delivered'", [$seller_id]);
        $salesData = $stmt->fetch();
        $totalSales = $salesData['total_sales'] ?? 0;
        
        // Fetch Escrow Balance
        $stmt2 = $db->query("SELECT escrow_balance FROM seller_profiles WHERE user_id = ?", [$seller_id]);
        $escrowData = $stmt2->fetch();
        $escrowBalance = $escrowData['escrow_balance'] ?? 0;
        
        // Advanced Analytics
        $activeProductsCount = count($products);
        
        $stmt3 = $db->query("SELECT COUNT(DISTINCT o.id) as pending_orders FROM orders o JOIN order_items oi ON o.id = oi.order_id JOIN products p ON oi.product_id = p.id WHERE p.seller_id = ? AND o.status IN ('pending', 'processing')", [$seller_id]);
        $pendingOrders = $stmt3->fetch()['pending_orders'] ?? 0;

        $stmt4 = $db->query("SELECT AVG(r.rating) as avg_rating FROM reviews r JOIN products p ON r.product_id = p.id WHERE p.seller_id = ?", [$seller_id]);
        $avgRating = $stmt4->fetch()['avg_rating'] ?? 0;

        require_once __DIR__ . '/../Views/seller/dashboard.php';
    }

    public function markAsSent() {
        Auth::isSellerOrAdmin();
        $order_id = $_POST['order_id'] ?? null;
        if ($order_id) {
            \App\Models\Order::updateStatus($order_id, 'in_transit');
        }
        header("Location: /dashboard");
        exit;
    }

    public function guidelines() {
        require_once __DIR__ . '/../Views/seller/guidelines.php';
    }

    public function addProductForm() {
        Auth::isSellerOrAdmin();
        $categories = \App\Models\Category::all();
        require_once __DIR__ . '/../Views/seller/add_product.php';
    }

    public function saveProduct() {
        Auth::isSellerOrAdmin();
        $seller_id = $_SESSION['user_id'];
        
        $title = $_POST['title'] ?? '';
        $category_id = $_POST['category_id'] ?? '';
        $price = $_POST['price'] ?? 0;
        $stock_quantity = $_POST['stock_quantity'] ?? 0;
        $description = $_POST['description'] ?? '';
        $is_on_sale = isset($_POST['is_on_sale']) ? 1 : 0;
        
        $image_url = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $mime_type = mime_content_type($_FILES['image']['tmp_name']);
            $base64 = base64_encode(file_get_contents($_FILES['image']['tmp_name']));
            $image_url = "data:$mime_type;base64,$base64";
        }

        $data = [
            'seller_id' => $seller_id,
            'category_id' => $category_id,
            'title' => $title,
            'price' => $price,
            'stock_quantity' => $stock_quantity,
            'description' => $description,
            'image_url' => $image_url,
            'is_on_sale' => $is_on_sale
        ];
        
        error_log("Attempting to insert product with data: " . print_r($data, true));

        try {
            \App\Models\Product::create($data);
            $message = "Product added successfully!";
            require_once __DIR__ . '/../Views/seller/success.php';
        } catch (\Exception $e) {
            error_log("Failed to insert product. PDO Exception: " . $e->getMessage());
            die("Database Error: " . $e->getMessage());
        }
    }

    public function editProductForm($id) {
        Auth::isSellerOrAdmin();
        $seller_id = $_SESSION['user_id'];
        $product = \App\Models\Product::find($id);
        
        if (!$product || $product['seller_id'] != $seller_id) {
            header("Location: /dashboard");
            exit;
        }

        $categories = \App\Models\Category::all();
        require_once __DIR__ . '/../Views/seller/edit_product.php';
    }

    public function updateProduct($id) {
        Auth::isSellerOrAdmin();
        $seller_id = $_SESSION['user_id'];
        
        $product = \App\Models\Product::find($id);
        if (!$product || $product['seller_id'] != $seller_id) {
            header("Location: /dashboard");
            exit;
        }

        $title = $_POST['title'] ?? '';
        $category_id = $_POST['category_id'] ?? '';
        $price = $_POST['price'] ?? 0;
        $stock_quantity = $_POST['stock_quantity'] ?? 0;
        $description = $_POST['description'] ?? '';
        $is_on_sale = isset($_POST['is_on_sale']) ? 1 : 0;

        $image_url = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $mime_type = mime_content_type($_FILES['image']['tmp_name']);
            $base64 = base64_encode(file_get_contents($_FILES['image']['tmp_name']));
            $image_url = "data:$mime_type;base64,$base64";
        }

        try {
            \App\Models\Product::update($id, $seller_id, [
                'category_id' => $category_id,
                'title' => $title,
                'price' => $price,
                'stock_quantity' => $stock_quantity,
                'description' => $description,
                'image_url' => $image_url,
                'is_on_sale' => $is_on_sale
            ]);
            $message = "Product updated successfully!";
            require_once __DIR__ . '/../Views/seller/success.php';
        } catch (\Exception $e) {
            error_log("Failed to update product. " . $e->getMessage());
            die("Database Error: " . $e->getMessage());
        }
    }

    public function deleteProduct($id) {
        Auth::isSellerOrAdmin();
        $seller_id = $_SESSION['user_id'];
        
        try {
            \App\Models\Product::delete($id, $seller_id);
            $message = "Product deleted successfully!";
            require_once __DIR__ . '/../Views/seller/success.php';
        } catch (\Exception $e) {
            error_log("Failed to delete product. " . $e->getMessage());
            die("Database Error: " . $e->getMessage());
        }
    }
}
