<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';
require_admin();

$page_title = 'User Management';
$pdo = get_db_connection();

$role_filter = isset($_GET['role']) ? $_GET['role'] : '';

$query = "SELECT id, name, email, role, created_at FROM users";
$params = [];
if ($role_filter) {
    $query .= " WHERE role = ?";
    $params[] = $role_filter;
}
$query .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$users = $stmt->fetchAll();

include __DIR__ . '/../includes/header.php';
?>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Users List</h5>
        <div class="d-flex gap-2">
            <form method="GET" class="d-flex align-items-center">
                <select name="role" class="form-select form-select-sm me-2" onchange="this.form.submit()">
                    <option value="">All Roles</option>
                    <option value="buyer" <?php echo $role_filter === 'buyer' ? 'selected' : ''; ?>>Buyers</option>
                    <option value="seller" <?php echo $role_filter === 'seller' ? 'selected' : ''; ?>>Sellers</option>
                    <option value="admin" <?php echo $role_filter === 'admin' ? 'selected' : ''; ?>>Admins</option>
                </select>
            </form>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-secondary rounded-circle d-flex justify-content-center align-items-center text-white me-2" style="width: 32px; height: 32px; font-size: 14px;">
                                    <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                                </div>
                                <strong><?php echo htmlspecialchars($user['name']); ?></strong>
                            </div>
                        </td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td>
                            <span class="badge bg-<?php echo match($user['role']) { 'admin' => 'danger', 'seller' => 'info text-dark', default => 'secondary' }; ?>">
                                <?php echo ucfirst($user['role']); ?>
                            </span>
                        </td>
                        <td><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                        <td>
                            <a href="user-detail.php?id=<?php echo urlencode($user['id']); ?>" class="btn btn-sm btn-outline-primary">View</a>
                            <?php if ($user['id'] !== $_SESSION['admin_id']): ?>
                            <form action="../actions/user-action.php" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to suspend this user?');">
                                <input type="hidden" name="action" value="suspend">
                                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger">Suspend</button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($users)): ?>
                    <tr><td colspan="5" class="text-center py-4 text-muted">No users found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
