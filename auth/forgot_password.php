<?php
session_start();
require_once 'db.php'; // Loads PDO $pdo instance

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    error_reporting(E_ALL);
    ini_set('display_errors', 0);

    $raw_input = file_get_contents('php://input');
    $input = json_decode($raw_input, true);
    if (!is_array($input)) {
        $input = $_POST;
    }

    $email = trim($input['email'] ?? '');

    // 1. Validation checks
    if (empty($email)) {
        echo json_encode(["status" => "error", "message" => "Please enter your Gmail address."]);
        exit();
    }

    $email_parts = explode('@', $email);
    $email_prefix = $email_parts[0] ?? '';
    $email_domain = strtolower($email_parts[1] ?? '');

    if ($email_domain !== 'gmail.com' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "error", "message" => "Please enter a valid Gmail address."]);
        exit();
    }

    if (!preg_match('/[a-zA-Z]/', $email_prefix)) {
        echo json_encode(["status" => "error", "message" => "Gmail address must contain at least one letter."]);
        exit();
    }

    try {
        // 2. Lookup registered user silently
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        $demo_link = null;

        if ($user) {
            // Invalidate any active unused reset tokens for this user
            $invalidateStmt = $pdo->prepare("UPDATE password_resets SET is_used = 1 WHERE user_id = ? AND is_used = 0");
            $invalidateStmt->execute([$user['id']]);

            // Create cryptographically secure random token
            $raw_token = bin2hex(random_bytes(32)); // 64 chars
            $token_hash = hash('sha256', $raw_token); // SHA-256 hash for storage

            // Insert into password_resets with 30-minute expiration
            $insertStmt = $pdo->prepare("
                INSERT INTO password_resets (user_id, token_hash, expires_at, is_used) 
                VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 30 MINUTE), 0)
            ");
            $insertStmt->execute([$user['id'], $token_hash]);

            // Construct local demo reset link
           $demo_link = "reset_password.php?token=" . $raw_token;
        }

        // 3. Return response with Demo Mode details if account exists
        if ($demo_link) {
            echo json_encode([
                "status" => "success",
                "message" => "Demo Mode: Your password reset link has been generated.",
                "demo_link" => $demo_link
            ]);
        } else {
            // Unregistered email returns generic message to prevent account enumeration
            echo json_encode([
                "status" => "success",
                "message" => "If this email is registered, you will receive instructions to reset your password."
            ]);
        }
        exit();

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "A server error occurred. Please try again later."]);
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password? - Gamora Gaming Cafe</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #0d1117; color: #c9d1d9; display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 20px; }
        .auth-card { background: #161b22; border: 1px solid #30363d; border-radius: 8px; padding: 30px; width: 100%; max-width: 440px; box-shadow: 0 8px 24px rgba(0,0,0,0.5); }
        .auth-card h2 { color: #58a6ff; font-size: 1.5rem; margin-bottom: 10px; text-align: center; }
        .auth-card p { font-size: 0.9rem; color: #8b949e; text-align: center; margin-bottom: 20px; line-height: 1.4; }
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; margin-bottom: 6px; font-size: 0.85rem; color: #8b949e; }
        .form-group input { width: 100%; padding: 10px 12px; background: #0d1117; border: 1px solid #30363d; border-radius: 6px; color: #c9d1d9; font-size: 0.95rem; }
        .form-group input:focus { border-color: #58a6ff; outline: none; }
        .btn-submit { width: 100%; padding: 10px; background: #238636; border: none; border-radius: 6px; color: #fff; font-size: 1rem; font-weight: 600; cursor: pointer; transition: background 0.2s; }
        .btn-submit:hover { background: #2ea043; }
        .alert { padding: 10px 12px; border-radius: 6px; font-size: 0.85rem; margin-bottom: 15px; display: none; text-align: center; word-break: break-all; }
        .alert-error { background: rgba(248,81,73,0.15); border: 1px solid #f85149; color: #f85149; }
        .alert-success { background: rgba(46,160,67,0.15); border: 1px solid #2ea043; color: #3fb950; }
        .demo-box { margin-top: 12px; padding: 10px; background: #0d1117; border: 1px dashed #58a6ff; border-radius: 6px; font-size: 0.85rem; }
        .demo-box a { color: #58a6ff; font-weight: 600; text-decoration: underline; }
        .back-link { display: block; text-align: center; margin-top: 20px; font-size: 0.85rem; color: #58a6ff; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="auth-card">
    <h2>Forgot Password?</h2>
    <p>Enter your registered Gmail address below to generate a password reset link.</p>

    <div id="alertMsg" class="alert"></div>

    <form id="forgotForm">
        <div class="form-group">
            <label for="email">Gmail Address</label>
            <input type="email" id="email" name="email" placeholder="example@gmail.com" required>
        </div>
        <button type="submit" class="btn-submit">Continue</button>
    </form>

    <a href="/login.php" class="back-link">&larr; Back to Login</a>
</div>

<script>
document.getElementById('forgotForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const email = document.getElementById('email').value;
    const alertMsg = document.getElementById('alertMsg');

    alertMsg.style.display = 'none';
    alertMsg.className = 'alert';

    try {
        const response = await fetch('forgot_password.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email: email })
        });

        const data = await response.json();

        if (data.status === 'success') {
            alertMsg.className = 'alert alert-success';
            let content = `<div>${data.message}</div>`;
            if (data.demo_link) {
                content += `<div class="demo-box"><a href="${data.demo_link}">Click here to Reset Password</a></div>`;
            }
            alertMsg.innerHTML = content;
            alertMsg.style.display = 'block';
            document.getElementById('forgotForm').reset();
        } else {
            alertMsg.className = 'alert alert-error';
            alertMsg.textContent = data.message;
            alertMsg.style.display = 'block';
        }
    } catch (err) {
        alertMsg.className = 'alert alert-error';
        alertMsg.textContent = 'An unexpected error occurred. Please try again.';
        alertMsg.style.display = 'block';
    }
});
</script>

</body>
</html>