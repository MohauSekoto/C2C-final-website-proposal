<?php
require_once __DIR__ . '/../app/Core/Database.php';

$db = new \App\Core\Database();
$pdo = $db->getConnection();

echo "Starting database seeding...\n";

// 1. Create tables
$pdo->exec("
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'seller', 'buyer') DEFAULT 'buyer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS seller_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    store_name VARCHAR(255) NOT NULL,
    store_description TEXT,
    location VARCHAR(255),
    escrow_balance DECIMAL(10, 2) DEFAULT 0.00,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    seller_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    stock_quantity INT DEFAULT 0,
    image_url VARCHAR(255),
    is_on_sale BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id),
    FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    status ENUM('pending', 'processing', 'in_transit', 'delivered', 'cancelled') DEFAULT 'pending',
    total_amount DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS wishlists (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY user_product (user_id, product_id)
);
");
echo "Tables Ensured.\n";

// 2. Clear existing data
$pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
$pdo->exec("TRUNCATE TABLE wishlists;");
$pdo->exec("TRUNCATE TABLE reviews;");
$pdo->exec("TRUNCATE TABLE order_items;");
$pdo->exec("TRUNCATE TABLE orders;");
$pdo->exec("TRUNCATE TABLE products;");
$pdo->exec("TRUNCATE TABLE seller_profiles;");
$pdo->exec("TRUNCATE TABLE categories;");
$pdo->exec("TRUNCATE TABLE users;");
$pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");
echo "Existing data cleared.\n";

// 3. Create Categories
$categories = [
    ['name' => 'Electronics', 'slug' => 'electronics'],
    ['name' => 'Fashion', 'slug' => 'fashion'],
    ['name' => 'Home', 'slug' => 'home'],
    ['name' => 'Handmade', 'slug' => 'handmade']
];
$catIds = [];
$stmt = $pdo->prepare("INSERT INTO categories (name, slug) VALUES (?, ?)");
foreach ($categories as $cat) {
    $stmt->execute([$cat['name'], $cat['slug']]);
    $catIds[$cat['slug']] = $pdo->lastInsertId();
}

// 4. Create Users
function createUser($pdo, $name, $email, $role) {
    $hash = password_hash('password123', PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $email, $hash, $role]);
    return $pdo->lastInsertId();
}

$adminId = createUser($pdo, 'Admin', 'admin@kasibuy.co.za', 'admin');
$buyer1 = createUser($pdo, 'Thabo Mbeki', 'thabo@example.com', 'buyer');
$buyer2 = createUser($pdo, 'Lerato Khumalo', 'lerato@example.com', 'buyer');
$buyer3 = createUser($pdo, 'Sipho Ndlovu', 'sipho@example.com', 'buyer');
$seller1 = createUser($pdo, 'Mpho Tech', 'mpho@example.com', 'seller');
$seller2 = createUser($pdo, 'Zanele Crafts', 'zanele@example.com', 'seller');

// 5. Create Seller Profiles
$stmt = $pdo->prepare("INSERT INTO seller_profiles (user_id, store_name, store_description, location, escrow_balance) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$seller1, 'Mpho Tech Hub', 'Best electronics in Soweto', 'Soweto, GP', 15000.00]);
$stmt->execute([$seller2, 'Zanele Handcrafts', 'Authentic handmade goods', 'Durban, KZN', 5400.00]);

// 6. Create Products
$products = [
    // Electronics (Seller 1)
    [$catIds['electronics'], $seller1, 'Wireless Over-Ear Headphones', 'High quality studio sound with noise cancellation.', 1299.99, 15, '/uploads/mock_electronics.png', 0],
    [$catIds['electronics'], $seller1, 'Smart Watch Series 5', 'Fitness tracking and notifications.', 2500.00, 8, '/uploads/mock_electronics.png', 1],
    [$catIds['electronics'], $seller1, 'Bluetooth Speaker', 'Portable waterproof speaker with bass.', 850.00, 20, '/uploads/mock_electronics.png', 0],
    
    // Fashion (Seller 2)
    [$catIds['fashion'], $seller2, 'Leather Tote Bag', 'Genuine leather stylish tote.', 850.00, 10, '/uploads/mock_fashion.png', 0],
    [$catIds['fashion'], $seller2, 'Summer Dress', 'Lightweight cotton summer dress.', 450.00, 25, '/uploads/mock_fashion.png', 1],
    
    // Home (Seller 1)
    [$catIds['home'], $seller1, 'Minimalist Table Lamp', 'Warm glow lamp for your bedside.', 350.00, 12, '/uploads/mock_home.png', 0],
    
    // Handmade (Seller 2)
    [$catIds['handmade'], $seller2, 'Ceramic Blue Mug', 'Handcrafted ceramic mug with deep blue glaze.', 180.00, 30, '/uploads/mock_handmade.png', 0],
    [$catIds['handmade'], $seller2, 'Woven Basket', 'Traditional hand-woven storage basket.', 250.00, 15, '/uploads/mock_handmade.png', 0],
];

$prodIds = [];
$stmt = $pdo->prepare("INSERT INTO products (category_id, seller_id, title, description, price, stock_quantity, image_url, is_on_sale) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
foreach ($products as $p) {
    $stmt->execute($p);
    $prodIds[] = $pdo->lastInsertId();
}

// 7. Create Reviews
$reviews = [
    [$prodIds[0], $buyer1, 5, 'Amazing sound quality! Highly recommend.'],
    [$prodIds[0], $buyer2, 4, 'Good but a bit tight on the ears.'],
    [$prodIds[3], $buyer3, 5, 'Beautiful bag, great craftsmanship.'],
    [$prodIds[6], $buyer1, 5, 'Love the glaze on this mug!'],
    [$prodIds[1], $buyer2, 3, 'Battery life could be better.'],
];

$stmt = $pdo->prepare("INSERT INTO reviews (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
foreach ($reviews as $r) {
    $stmt->execute($r);
}

// 8. Create Orders
$stmtOrder = $pdo->prepare("INSERT INTO orders (user_id, status, total_amount) VALUES (?, ?, ?)");
$stmtItem = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");

// Order 1 (Delivered)
$stmtOrder->execute([$buyer1, 'delivered', 1299.99]);
$order1Id = $pdo->lastInsertId();
$stmtItem->execute([$order1Id, $prodIds[0], 1, 1299.99]);

// Order 2 (In Transit)
$stmtOrder->execute([$buyer2, 'in_transit', 180.00]);
$order2Id = $pdo->lastInsertId();
$stmtItem->execute([$order2Id, $prodIds[6], 1, 180.00]);

echo "Seeding completed successfully!\n";
