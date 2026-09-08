<?php
session_start();

// 1. Session Login Protection
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// 2. Step up out of 'drpdwn/' and load 'auth/db.php'
require_once __DIR__ . '/../auth/db.php';

try {
    // 3. Fetch Logged-in User Data
    $stmt = $pdo->prepare("SELECT username, email, created_at FROM users WHERE id = :id");
    $stmt->execute([':id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
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
        body {
            background-color: #0f111a;
            color: #ffffff;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .profile-card {
            background-color: #1a1d2e;
            border: 1px solid #2d3248;
            border-radius: 12px;
            padding: 30px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
        }
        .profile-card h2 {
            color: #00f5d4;
            margin-top: 0;
            border-bottom: 1px solid #2d3248;
            padding-bottom: 10px;
        }
        .info-group {
            margin-bottom: 15px;
        }
        .info-group label {
            display: block;
            font-size: 0.85rem;
            color: #a0aab8;
            margin-bottom: 4px;
        }
        .info-group span {
            font-size: 1.1rem;
            font-weight: bold;
        }
        .btn-back {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 18px;
            background-color: #7b2cbf;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            text-align: center;
        }
        .btn-back:hover {
            background-color: #9d4edd;
        }
    </style>
</head>
<body>

    <div class="profile-card">
        <h2>User Profile</h2>
        
        <div class="info-group">
            <label>Username</label>
            <span>@<?php echo htmlspecialchars($user['username']); ?></span>
        </div>

        <div class="info-group">
            <label>Email Address</label>
            <span><?php echo htmlspecialchars($user['email'] ?? 'Not set'); ?></span>
        </div>

        <div class="info-group">
            <label>Member Since</label>
            <span><?php echo !empty($user['created_at']) ? date('F d, Y', strtotime($user['created_at'])) : 'N/A'; ?></span>
        </div>

        <a href="../index.php" class="btn-back">Back to Home</a>
    </div>

</body>
</html>