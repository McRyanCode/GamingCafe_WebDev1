<?php
session_start();

// Protection: Check if logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once '../auth/db.php';

// Protection: Verify user has 'admin' role
$roleStmt = $pdo->prepare("SELECT role FROM users WHERE id = :id");
$roleStmt->execute([':id' => $_SESSION['user_id']]);
$user = $roleStmt->fetch();

if (!$user || $user['role'] !== 'admin') {
    http_response_code(403);
    die("Access Denied: You do not have administrator permissions.");
}

// Fetch all customers with their total bookings count
$query = "SELECT u.id, u.username, u.email, u.created_at, 
                 COUNT(b.id) AS total_bookings
          FROM users u
          LEFT JOIN bookings b ON u.id = b.user_id
          WHERE u.role != 'admin' OR u.role IS NULL
          GROUP BY u.id
          ORDER BY u.id DESC";

$customers = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Customer List | Gamora Cafe</title>
    <style>
        body { background: #0f111a; color: #fff; font-family: Arial, sans-serif; padding: 30px; }
        .admin-card { background: #1a1d2e; border: 1px solid #2d3248; border-radius: 10px; padding: 25px; }
        h1 { color: #00f5d4; margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #2d3248; }
        th { background: #252a40; color: #a0aab8; }
        tr:hover { background: #222738; }
        .badge { background: #7b2cbf; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem; }
        .btn-home { color: #00f5d4; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

    <div class="admin-card">
        <a href="../index.php" class="btn-home">&larr; Back to Main Site</a>
        <h1>Customer Directory</h1>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Joined Date</th>
                    <th>Total Bookings</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($customers)): ?>
                    <tr><td colspan="5">No customers registered yet.</td></tr>
                <?php else: ?>
                    <?php foreach ($customers as $c): ?>
                        <tr>
                            <td>#<?php echo $c['id']; ?></td>
                            <td><strong>@<?php echo htmlspecialchars($c['username']); ?></strong></td>
                            <td><?php echo htmlspecialchars($c['email'] ?? 'N/A'); ?></td>
                            <td><?php echo date('M d, Y', strtotime($c['created_at'] ?? 'now')); ?></td>
                            <td><span class="badge"><?php echo $c['total_bookings']; ?> Bookings</span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>