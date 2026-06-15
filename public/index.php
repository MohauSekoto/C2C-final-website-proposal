<?php
// public/index.php

// Start the session for authentication and CSRF
session_start();

// Auto-loader for basic namespacing
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

use App\Core\Router;

// Initialize the Router
$router = new Router();

// Define Routes
$router->get('/', 'HomeController@index');
$router->get('/contact', 'HomeController@contact');
$router->get('/terms', 'HomeController@terms');

$router->get('/products', 'ProductController@index');
$router->get('/categories', 'CategoryController@index');
$router->get('/product/{id}', 'ProductController@show');
$router->post('/product/{id}/review', 'ProductController@addReview');

$router->get('/login', 'AuthController@loginForm');
$router->post('/login', 'AuthController@login');
$router->get('/register', 'AuthController@registerForm');
$router->post('/register', 'AuthController@register');
$router->get('/register-store', 'AuthController@registerStoreForm');
$router->post('/register-store', 'AuthController@registerStore');
$router->get('/logout', 'AuthController@logout');

$router->get('/profile', 'ProfileController@index');
$router->post('/profile/update', 'ProfileController@update');
$router->post('/profile/order/confirm-receipt', 'ProfileController@confirmReceipt');
$router->post('/wishlist/toggle', 'ProfileController@toggleWishlist');

$router->get('/cart', 'CartController@index');
$router->post('/cart/add', 'CartController@add');
$router->post('/cart/remove', 'CartController@remove');
$router->post('/cart/update', 'CartController@update');
$router->get('/checkout', 'CartController@checkout');
$router->post('/checkout', 'CartController@processCheckout');
$router->get('/checkout/success', 'CartController@success');

$router->get('/dashboard', 'SellerController@index');
$router->get('/guidelines', 'SellerController@guidelines');

// Seller Product Management
$router->get('/seller/product/add', 'SellerController@addProductForm');
$router->post('/seller/product/add', 'SellerController@saveProduct');
$router->get('/seller/product/edit/{id}', 'SellerController@editProductForm');
$router->post('/seller/product/edit/{id}', 'SellerController@updateProduct');
$router->post('/seller/order/mark-sent', 'SellerController@markAsSent');

$router->get('/admin/login', 'AdminController@loginForm');
$router->post('/admin/login', 'AdminController@login');
$router->get('/admin/dashboard', 'AdminController@index');
$router->get('/admin/users', 'AdminController@users');
$router->post('/admin/users/ban', 'AdminController@banUser');
$router->get('/admin/products', 'AdminController@products');
$router->post('/admin/products/delete', 'AdminController@deleteProduct');
$router->get('/admin/orders', 'AdminController@orders');
$router->post('/admin/orders/update', 'AdminController@updateOrder');
$router->get('/admin/database', 'AdminController@databaseIndex');
$router->get('/admin/database/table/{table}', 'AdminController@databaseTable');
$router->get('/admin/database/form/{table}', 'AdminController@databaseForm');
$router->get('/admin/database/form/{table}/{id}', 'AdminController@databaseForm');
$router->post('/admin/database/save/{table}', 'AdminController@databaseSave');
$router->post('/admin/database/delete/{table}', 'AdminController@databaseDelete');

// Run the router
$router->run();

