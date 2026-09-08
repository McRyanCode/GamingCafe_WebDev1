<?php
session_start();

// Protection: Verify logged-in session
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once __DIR__ . '/../auth/db.php';

// Protection: Verify Admin Role
$roleStmt = $pdo->prepare("SELECT role FROM users WHERE id = :id");
$roleStmt->execute([':id' => $_SESSION['user_id']]);
$currentUser = $roleStmt->fetch();

if (!$currentUser || $currentUser['role'] !== 'admin') {
    http_response_code(403);
    die("Access Denied: Admin privileges required.");
}

$message = '';

// --- 1. CREATE USER ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_create_user'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)");
        $stmt->execute([':username' => $username, ':email' => $email, ':password' => $password, ':role' => $role]);
        $message = "User created successfully!";
    } catch (PDOException $e) { $message = "Error creating user: " . $e->getMessage(); }
}

// --- 2. UPDATE USER ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_update_user'])) {
    $id = $_POST['user_id'];
    $email = trim($_POST['email']);
    $role = $_POST['role'];

    try {
        $stmt = $pdo->prepare("UPDATE users SET email = :email, role = :role WHERE id = :id");
        $stmt->execute([':email' => $email, ':role' => $role, ':id' => $id]);
        $message = "User #$id updated successfully!";
    } catch (PDOException $e) { $message = "Error updating user: " . $e->getMessage(); }
}

// --- 3. DELETE USER ---
if (isset($_GET['delete_user'])) {
    $delete_id = $_GET['delete_user'];
    if ($delete_id == $_SESSION['user_id']) {
        $message = "You cannot delete your own active admin account!";
    } else {
        try {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
            $stmt->execute([':id' => $delete_id]);
            $message = "User #$delete_id deleted successfully!";
        } catch (PDOException $e) { $message = "Error deleting user: " . $e->getMessage(); }
    }
}

// --- FETCH USERS ---
$users = $pdo->query("SELECT u.id, u.username, u.email, u.role, COUNT(b.id) AS total_bookings
                      FROM users u
                      LEFT JOIN bookings b ON u.id = b.user_id
                      GROUP BY u.id ORDER BY u.id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Management | Gamora Admin</title>
    <style>
        body { background: #0f111a; color: #fff; font-family: Arial, sans-serif; padding: 25px; }
        .container { max-width: 1100px; margin: 0 auto; }
        .admin-nav { display: flex; gap: 15px; margin-bottom: 20px; align-items: center; }
        .admin-nav a { color: #a0aab8; text-decoration: none; padding: 8px 16px; border-radius: 6px; background: #1a1d2e; }
        .admin-nav a.active { background: #7b2cbf; color: #fff; font-weight: bold; }
        .btn-home { color: #00f5d4 !important; background: transparent !important; margin-right: auto; }
        .card { background: #1a1d2e; border: 1px solid #2d3248; border-radius: 10px; padding: 20px; margin-bottom: 25px; }
        h1, h2 { color: #00f5d4; margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #2d3248; }
        th { background: #252a40; color: #a0aab8; }
        .btn { padding: 5px 10px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; text-decoration: none; font-size: 0.85rem; }
        .btn-add { background: #00f5d4; color: #0f111a; }
        .btn-save { background: #7b2cbf; color: #fff; }
        .btn-del { background: #ff4d4d; color: #fff; }
        input, select { background: #0f111a; border: 1px solid #2d3248; color: #fff; padding: 5px; border-radius: 4px; }
        .alert { background: #7b2cbf; color: #fff; padding: 10px; border-radius: 6px; margin-bottom: 15px; }
    </style>
</head>
<body>

<div class="container">
    <div class="admin-nav">
        <a href="../index.php" class="btn-home">&larr; Back to Main Site</a>
        <a href="customers.php" class="active">👥 Customers</a>
        <a href="bookings.php">🖥️ Bookings</a>
    </div>

    <h1>Customer Management</h1>

    <?php if ($message): ?>
        <div class="alert"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <!-- CREATE USER -->
    <div class="card">
        <h2>Create New User</h2>
        <form method="POST" style="display: flex; gap: 10px; flex-wrap: wrap;">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="role">
                <option value="customer">Customer</option>
                <option value="admin">Admin</option>
            </select>
            <button type="submit" name="action_create_user" class="btn btn-add">+ Add User</button>
        </form>
    </div>

    <!-- USER LIST -->
    <div class="card">
        <h2>Registered Accounts</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Bookings</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <form method="POST">
                            <td>#<?php echo $u['id']; ?><input type="hidden" name="user_id" value="<?php echo $u['id']; ?>"></td>
                            <td><strong>@<?php echo htmlspecialchars($u['username']); ?></strong></td>
                            <td><input type="email" name="email" value="<?php echo htmlspecialchars($u['email'] ?? ''); ?>"></td>
                            <td>
                                <select name="role">
                                    <option value="customer" <?php echo $u['role'] === 'customer' ? 'selected' : ''; ?>>Customer</option>
                                    <option value="admin" <?php echo $u['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                </select>
                            </td>
                            <td><?php echo $u['total_bookings']; ?></td>
                            <td>
                                <button type="submit" name="action_update_user" class="btn btn-save">Save</button>
                                <a href="?delete_user=<?php echo $u['id']; ?>" class="btn btn-del" onclick="return confirm('Delete user?');">Delete</a>
                            </td>
                        </form>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>