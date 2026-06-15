<?php
// app/Controllers/HomeController.php
namespace App\Controllers;

use App\Core\Database;

class HomeController {
    public function index() {
        $trendingProducts = \App\Models\Product::all(['sort' => 'newest']);
        $trendingProducts = array_slice($trendingProducts, 0, 8); // Limit to 8
        
        $title = "KasiBuy - Home";
        $view = __DIR__ . '/../Views/home.php';
        
        require_once __DIR__ . '/../Views/layout.php';
    }

    public function contact() {
        require_once __DIR__ . '/../Views/contact.php';
    }

    public function terms() {
        require_once __DIR__ . '/../Views/terms.php';
    }
}
