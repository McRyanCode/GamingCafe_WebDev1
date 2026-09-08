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

// --- UPDATE BOOKING STATUS ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_update_booking'])) {
    $booking_id = $_POST['booking_id'];
    $status = $_POST['status'];

    try {
        $stmt = $pdo->prepare("UPDATE bookings SET status = :status WHERE id = :id");
        $stmt->execute([':status' => $status, ':id' => $booking_id]);
        $message = "Booking #$booking_id status updated to '$status'!";
    } catch (PDOException $e) { $message = "Error updating booking: " . $e->getMessage(); }
}

// --- DELETE BOOKING ---
if (isset($_GET['delete_booking'])) {
    $booking_id = $_GET['delete_booking'];
    try {
        $stmt = $pdo->prepare("DELETE FROM bookings WHERE id = :id");
        $stmt->execute([':id' => $booking_id]);
        $message = "Booking #$booking_id removed permanently!";
    } catch (PDOException $e) { $message = "Error removing booking: " . $e->getMessage(); }
}

// --- FETCH BOOKINGS ---
// Fetch all bookings with user details and timestamps
$bookings = $pdo->query("SELECT b.id, b.user_id, b.device_type, b.duration_hours, 
                                b.pricing_tier, b.status, b.created_at, u.username 
                         FROM bookings b 
                         JOIN users u ON b.user_id = u.id 
                         ORDER BY b.id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Management | Gamora Admin</title>
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
        .btn-save { background: #7b2cbf; color: #fff; }
        .btn-del { background: #ff4d4d; color: #fff; }
        select { background: #0f111a; border: 1px solid #2d3248; color: #fff; padding: 5px; border-radius: 4px; }
        .alert { background: #7b2cbf; color: #fff; padding: 10px; border-radius: 6px; margin-bottom: 15px; }
    </style>
</head>
<body>

<div class="container">
    <div class="admin-nav">
        <a href="../index.php" class="btn-home">&larr; Back to Main Site</a>
        <a href="customers.php">👥 Customers</a>
        <a href="bookings.php" class="active">🖥️ Bookings</a>
    </div>

    <h1>Booking Management</h1>

    <?php if ($message): ?>
        <div class="alert"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <!-- BOOKINGS TABLE -->
    <div class="card">
        <h2>Live Reservations</h2>
        <table>
            <!-- Table Header -->
<thead>
    <tr>
        <th>ID</th>
        <th>Customer</th>
        <th>Device</th>
        <th>Tier</th>
        <th>Reserved At</th> <!-- New Time Column -->
        <th>Status</th>
        <th>Actions</th>
    </tr>
</thead>

<!-- Table Body -->
<tbody>
    <?php if (empty($bookings)): ?>
        <tr><td colspan="7">No bookings found.</td></tr>
    <?php else: ?>
        <?php foreach ($bookings as $b): ?>
            <tr>
                <form method="POST">
                    <td>#<?php echo $b['id']; ?><input type="hidden" name="booking_id" value="<?php echo $b['id']; ?>"></td>
                    <td><strong>@<?php echo htmlspecialchars($b['username']); ?></strong></td>
                    <td><?php echo htmlspecialchars($b['device_type']); ?> (<?php echo $b['duration_hours']; ?>h)</td>
                    <td><?php echo htmlspecialchars($b['pricing_tier']); ?></td>
                    
                    <!-- DISPLAY TIME AND DATE HERE -->
                    <td>
                        <span style="color: #00f5d4; font-weight: bold;">
                            <?php echo date('M d, Y', strtotime($b['created_at'])); ?>
                        </span>
                        <br>
                        <small style="color: #a0aab8;">
                            <?php echo date('h:i A', strtotime($b['created_at'])); ?>
                        </small>
                    </td>

                    <td>
                        <select name="status">
                            <option value="Pending" <?php echo $b['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="Confirmed" <?php echo $b['status'] === 'Confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                            <option value="Completed" <?php echo $b['status'] === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                            <option value="Cancelled" <?php echo $b['status'] === 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                    </td>
                    <td>
                        <button type="submit" name="action_update_booking" class="btn btn-save">Update</button>
                        <a href="?delete_booking=<?php echo $b['id']; ?>" 
                           class="btn btn-del" 
                           onclick="return confirm('Delete booking #<?php echo $b['id']; ?> reserved on <?php echo date('M d, Y \a\t h:i A', strtotime($b['created_at'])); ?>?');">
                           Remove
                        </a>
                    </td>
                </form>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</tbody>
        </table>
    </div>
</div>

</body>
</html>