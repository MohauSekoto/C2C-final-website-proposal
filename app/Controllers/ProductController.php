<?php
namespace App\Controllers;

use App\Models\Product;

class ProductController {
    public function index() {
        $filters = [
            'categories' => $_GET['category'] ?? [],
            'min_price' => $_GET['min_price'] ?? null,
            'max_price' => $_GET['max_price'] ?? null,
            'sort' => $_GET['sort'] ?? 'newest',
            'search' => $_GET['search'] ?? null
        ];
        
        if (!is_array($filters['categories']) && !empty($filters['categories'])) {
            $filters['categories'] = [$filters['categories']];
        }

        $products = Product::all($filters);
        $allCategories = \App\Models\Category::all();

        $view = __DIR__ . '/../Views/products.php';
        require_once __DIR__ . '/../Views/layout.php';
    }

    public function show($id) {
        $product = Product::find($id);
        if (!$product) {
            header("Location: /products");
            exit;
        }
        $reviews = Product::getReviews($id);
        require_once __DIR__ . '/../Views/product_detail.php';
    }

    public function addReview($id) {
        \App\Core\Auth::requireLogin();
        $user_id = $_SESSION['user_id'];
        $rating = (int)($_POST['rating'] ?? 0);
        $comment = $_POST['comment'] ?? '';
        
        if ($rating >= 1 && $rating <= 5) {
            $db = new \App\Core\Database();
            $db->query("INSERT INTO reviews (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?)", [$id, $user_id, $rating, $comment]);
        }
        
        header("Location: /product/" . $id);
        exit;
    }
}
