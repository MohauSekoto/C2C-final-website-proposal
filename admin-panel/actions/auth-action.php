<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        header("Location: ../pages/login.php?error=Please fill in all fields.");
        exit;
    }

    try {
        $pdo = get_db_connection();
        if (login_admin($pdo, $email, $password)) {
            header("Location: ../pages/dashboard.php");
            exit;
        } else {
            header("Location: ../pages/login.php?error=Invalid email or password.");
            exit;
        }
    } catch (\PDOException $e) {
        // In production, log the error and show generic message
        header("Location: ../pages/login.php?error=Database connection failed.");
        exit;
    }
} else {
    header("Location: ../pages/login.php");
    exit;
}
?>
