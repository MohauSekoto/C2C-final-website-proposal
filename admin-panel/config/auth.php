<?php
session_start();

function is_logged_in() {
    return isset($_SESSION['admin_id']);
}

function require_admin() {
    if (!is_logged_in()) {
        header("Location: /pages/login.php");
        exit;
    }
}

function login_admin($pdo, $email, $password) {
    $stmt = $pdo->prepare("SELECT id, password_hash, role FROM users WHERE email = ? AND role = 'admin'");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Note: bcryptjs hashes generated in Node are compatible with PHP's password_verify
    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['admin_id'] = $user['id'];
        return true;
    }
    return false;
}
?>
