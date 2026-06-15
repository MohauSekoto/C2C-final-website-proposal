<?php
namespace App\Controllers;

use App\Models\Category;

class CategoryController {
    public function index() {
        // We will fetch categories from the DB or just use the 6 main ones
        // Actually, the database already has them from our previous model update.
        $categories = Category::all();
        
        $title = "KasiBuy - All Categories";
        $view = __DIR__ . '/../Views/categories.php';
        
        require_once __DIR__ . '/../Views/layout.php';
    }
}
