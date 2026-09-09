<?php
session_start();

// Security Check: Enforce Login
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once '../auth/db.php';

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'Gamer';
$bookings = [];

try {
    // Parameterized retrieval (pricing_tier removed from query)
    $sql = "SELECT id, device_id, device_type, booking_date, start_time, time_mode, duration_hours, total_price, status 
            FROM bookings 
            WHERE user_id = :user_id 
            ORDER BY booking_date DESC, start_time DESC";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $user_id]);
    $bookings = $stmt->fetchAll();
} catch (PDOException $e) {
    $error_message = "Unable to retrieve bookings at this time.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - Gamora Gaming Cafe</title>
    <link rel="stylesheet" href="my_bookings.css">
    <style>
        /* Top Navigation Link Styling */
        .top-nav-bar {
            width: 100%;
            margin-bottom: 20px;
        }

        .back-home-link {
            color: #00f5d4;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            display: inline-block;
            transition: color 0.2s ease, transform 0.2s ease;
        }

        .back-home-link:hover {
            color: #4895ef;
            transform: translateX(-3px);
        }
    </style>
</head>
<body>
    <div class="bookings-container">
        
        <!-- Top Navigation Bar -->
        <div class="top-nav-bar">
            <a href="../index.php" class="back-home-link">&larr; Back to Home</a>
        </div>

        <header class="bookings-header">
            <h1>My Bookings</h1>
            <p>Welcome back, <strong><?php echo htmlspecialchars($username); ?></strong>! Here is your reservation history.</p>
        </header>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <?php if (empty($bookings)): ?>
            <div class="empty-state">
                <div class="empty-icon">🎮</div>
                <h2>No bookings yet.</h2>
                <p>Looks like you haven't reserved any PC or Console stations yet.</p>
                <a href="booking.php" class="btn-primary">Reserve a Station Now</a>
            </div>
        <?php else: ?>
            <div class="bookings-grid">
                <?php foreach ($bookings as $b): ?>
                    <div class="booking-card">
                        <div class="card-header">
                            <span class="booking-id">#BOOK-<?php echo str_pad($b['id'], 5, '0', STR_PAD_LEFT); ?></span>
                            <span class="status-badge status-<?php echo strtolower($b['status']); ?>">
                                <?php echo htmlspecialchars($b['status']); ?>
                            </span>
                        </div>

                        <div class="card-body">
                            <div class="device-info">
                                <span class="device-icon"><?php echo $b['device_type'] === 'Console' ? '🎮' : '🖥️'; ?></span>
                                <div>
                                    <h3><?php echo htmlspecialchars($b['device_id']); ?></h3>
                                    <span class="device-type"><?php echo htmlspecialchars($b['device_type']); ?></span>
                                </div>
                            </div>

                            <div class="booking-details">
                                <div class="detail-item">
                                    <span class="label">Date:</span>
                                    <span class="value"><?php echo date('M d, Y', strtotime($b['booking_date'])); ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="label">Start Time:</span>
                                    <span class="value"><?php echo date('h:i A', strtotime($b['start_time'])); ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="label">Duration:</span>
                                    <span class="value">
                                        <?php 
                                            echo $b['time_mode'] === 'Open' 
                                                ? 'Open Time' 
                                                : htmlspecialchars($b['duration_hours']) . ' Hour(s)'; 
                                        ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <span class="price-label">Total Price</span>
                            <span class="price-amount">₱<?php echo number_format($b['total_price'], 2); ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="actions-bar">
                <a href="booking.php" class="btn-secondary">+ New Reservation</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>