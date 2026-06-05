<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';
require_admin();

$action = $_POST['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = get_db_connection();

    if ($action === 'change_role') {
        $user_id = $_POST['user_id'] ?? '';
        $new_role = $_POST['new_role'] ?? '';
        
        if ($user_id && in_array($new_role, ['buyer', 'seller', 'admin']) && $user_id !== $_SESSION['admin_id']) {
            $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
            $stmt->execute([$new_role, $user_id]);
        }
        header("Location: ../pages/user-detail.php?id=" . urlencode($user_id));
        exit;
    }
    
    // Future actions like suspend could be added here
}

header("Location: ../pages/users.php");
exit;
?>
