

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// 1. Protection: Check if logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

// 2. Load Database Connection
require_once __DIR__ . '/../auth/db.php';

try {
    // 3. Fetch Logged-in User Data
    $stmt = $pdo->prepare("SELECT username, email, role, created_at FROM users WHERE id = :id");
    $stmt->execute([':id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // 4. Query most booked device for Favorite Station
    $favStmt = $pdo->prepare("SELECT device_type, COUNT(*) as total FROM bookings WHERE user_id = :id GROUP BY device_type ORDER BY total DESC LIMIT 1");
    $favStmt->execute([':id' => $_SESSION['user_id']]);
    $favResult = $favStmt->fetch(PDO::FETCH_ASSOC);

    // FIX 1: Only assign device_type if a booking actually exists; otherwise return 'No booking yet'
    $favoriteStation = ($favResult && !empty($favResult['device_type'])) ? $favResult['device_type'] : 'No booking yet';

    // 5. Query latest Pricing Tier from bookings
    $tierStmt = $pdo->prepare("SELECT pricing_tier FROM bookings WHERE user_id = :id ORDER BY id DESC LIMIT 1");
    $tierStmt->execute([':id' => $_SESSION['user_id']]);
    $tierResult = $tierStmt->fetch(PDO::FETCH_ASSOC);

    // FIX 2: Assign 'No booking yet' if no tier record exists
    $pricingTier = ($tierResult && !empty($tierResult['pricing_tier'])) ? $tierResult['pricing_tier'] : 'No booking yet';

} catch (PDOException $e) {
    die("Error retrieving profile data: " . htmlspecialchars($e->getMessage()));
}

if (!$user) {
    die("User profile not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Gamora Gaming Cafe</title>
    <style>
        * { box-sizing: border-box; }
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-family: Arial, sans-serif;
            color: #ffffff;
            overflow: hidden;
        }

        /* 1. Full-screen iframe rendering the real live site in background */
        .site-bg-frame {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            border: none;
            /* Applies heavy blur + dimming over the active website */
            filter: blur(8px) brightness(0.35) contrast(1.1);
            transform: scale(1.05); /* Prevents white edges from blur */
            z-index: 1;
            pointer-events: none; /* Disables interaction with background */
        }

        /* 2. Overlay wrapper for centering card */
        .profile-overlay {
            position: relative;
            z-index: 2;
            width: 100%;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        /* 3. Centered Reference Card */
        .profile-card {
            background: rgba(26, 29, 46, 0.88);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 16px;
            padding: 35px 30px;
            width: 100%;
            max-width: 380px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.7);
            text-align: center;
        }

        /* Avatar Container & Online Status Badge */
        .avatar-wrapper {
            position: relative;
            width: 105px;
            height: 105px;
            margin: 0 auto 15px auto;
        }

        .avatar-img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #7b2cbf;
        }

        .online-badge {
            position: absolute;
            top: 0;
            right: -8px;
            background: rgba(0, 245, 212, 0.15);
            border: 1px solid #00f5d4;
            color: #00f5d4;
            font-size: 0.75rem;
            font-weight: bold;
            padding: 3px 8px;
            border-radius: 12px;
        }

        /* User Header Details */
        .user-name {
            font-size: 1.35rem;
            font-weight: bold;
            margin: 5px 0 2px 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .role-tag {
            background: #7b2cbf;
            color: #fff;
            font-size: 0.65rem;
            padding: 2px 6px;
            border-radius: 4px;
            text-transform: uppercase;
        }

        .user-handle {
            color: #a0aab8;
            font-size: 0.88rem;
            margin-bottom: 20px;
        }

        /* Primary Action Button */
        .btn-outline {
            display: block;
            width: 100%;
            padding: 10px 0;
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            font-size: 0.9rem;
            margin-bottom: 22px;
            transition: all 0.2s ease;
        }

        .btn-outline:hover {
            border-color: #00f5d4;
            color: #00f5d4;
            background: rgba(0, 245, 212, 0.05);
        }

        /* Metadata List */
        .meta-list {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 15px;
            text-align: left;
        }

        .meta-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            font-size: 0.9rem;
        }

        .meta-label {
            color: #a0aab8;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .meta-value {
            font-weight: bold;
            color: #ffffff;
        }

        /* Bottom Back Button */
        .btn-back {
            display: block;
            width: 100%;
            margin-top: 20px;
            padding: 10px 0;
            background: #7b2cbf;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 0.9rem;
            transition: background 0.2s ease;
        }

        .btn-back:hover { background: #9d4edd; }
    </style>
</head>
<body>

    <!-- Blurred Live Website Background -->
    <iframe class="site-bg-frame" src="../index.php"></iframe>

    <!-- Overlay Wrapper & Card -->
    <div class="profile-overlay">
        <div class="profile-card">
            
            <!-- Avatar + Online Status Badge -->
            <div class="avatar-wrapper">
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($user['username']); ?>&background=7b2cbf&color=fff&size=128" alt="Avatar" class="avatar-img">
                <span class="online-badge">• Online</span>
            </div>

            <!-- Username & Email -->
            <div class="user-name">
                <?php echo htmlspecialchars($user['username']); ?>
                <?php if (!empty($user['role']) && $user['role'] === 'admin'): ?>
                    <span class="role-tag">Admin</span>
                <?php endif; ?>
            </div>
            <div class="user-handle"><?php echo htmlspecialchars($user['email'] ?? 'No email registered'); ?></div>

            <!-- Action Button -->
            <a href="../booking/my_bookings.php" class="btn-outline">View My Bookings</a>

            <!-- Metadata List -->
            <div class="meta-list">
                <div class="meta-item">
                    <span class="meta-label">🎮 Favorite Station</span>
                    <span class="meta-value" style="color: #00f5d4;"><?php echo htmlspecialchars($favoriteStation); ?></span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">⚡ Pricing Tier</span>
                    <span class="meta-value" style="color: #00f5d4;"><?php echo htmlspecialchars($pricingTier); ?></span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">👤 Member since</span>
                    <span class="meta-value"><?php echo !empty($user['created_at']) ? date('M Y', strtotime($user['created_at'])) : 'Nov 2024'; ?></span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">⚡ Status</span>
                    <span class="meta-value" style="color: #00f5d4;">Active Gamer</span>
                </div>
            </div>

            <a href="../index.php" class="btn-back">Back to Home</a>
        </div>
    </div>

</body>
</html>