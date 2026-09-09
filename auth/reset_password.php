<?php
session_start();
require_once 'db.php';

$raw_token = trim($_GET['token'] ?? $_POST['token'] ?? '');
$token_valid = false;
$error_message = '';

// Helper function to verify token against database
function verifyToken($pdo, $raw_token) {
    if (empty($raw_token)) return false;
    $token_hash = hash('sha256', $raw_token);
    
    $stmt = $pdo->prepare("
        SELECT id, user_id, expires_at, is_used 
        FROM password_resets 
        WHERE token_hash = ? AND is_used = 0 AND expires_at > NOW() 
        LIMIT 1
    ");
    $stmt->execute([$token_hash]);
    return $stmt->fetch();
}

// Handle Form Submission (AJAX POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    error_reporting(E_ALL);
    ini_set('display_errors', 0);

    $raw_input = file_get_contents('php://input');
    $input = json_decode($raw_input, true);
    if (!is_array($input)) {
        $input = $_POST;
    }

    $token    = trim($input['token'] ?? '');
    $password = $input['password'] ?? '';
    $confirm  = $input['confirm_password'] ?? $input['confirm'] ?? '';

    // 1. Verify token validity on server
    $reset_record = verifyToken($pdo, $token);
    if (!$reset_record) {
        echo json_encode(["status" => "error", "message" => "This password reset link is invalid or has expired."]);
        exit();
    }

    // 2. Validate new password rules
    if (strlen($password) < 8) {
        echo json_encode(["status" => "error", "message" => "Password must be at least 8 characters."]);
        exit();
    }

    if (!preg_match('/[a-zA-Z]/', $password)) {
        echo json_encode(["status" => "error", "message" => "Password must contain at least one letter."]);
        exit();
    }

    if (!preg_match('/[0-9]/', $password)) {
        echo json_encode(["status" => "error", "message" => "Password must contain at least one number."]);
        exit();
    }

    if ($password !== $confirm) {
        echo json_encode(["status" => "error", "message" => "Passwords do not match."]);
        exit();
    }

    try {
        $pdo->beginTransaction();

        // 3. Update user's password with secure hash
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $updateUser = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $updateUser->execute([$hashed_password, $reset_record['user_id']]);

        // 4. Mark token as used
        $updateToken = $pdo->prepare("UPDATE password_resets SET is_used = 1 WHERE id = ?");
        $updateToken->execute([$reset_record['id']]);

        $pdo->commit();

        echo json_encode([
            "status" => "success", 
            "message" => "Your password has been successfully reset. You can now log in."
        ]);
        exit();

    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "A server error occurred. Please try again later."]);
        exit();
    }
}

// Initial GET Page Load Check
$reset_record = verifyToken($pdo, $raw_token);
if ($reset_record) {
    $token_valid = true;
} else {
    $error_message = "This password reset link is invalid or has expired.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Gamora Gaming Cafe</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #0d1117; color: #c9d1d9; display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 20px; }
        .auth-card { background: #161b22; border: 1px solid #30363d; border-radius: 8px; padding: 30px; width: 100%; max-width: 400px; box-shadow: 0 8px 24px rgba(0,0,0,0.5); }
        .auth-card h2 { color: #58a6ff; font-size: 1.5rem; margin-bottom: 10px; text-align: center; }
        .auth-card p { font-size: 0.9rem; color: #8b949e; text-align: center; margin-bottom: 20px; line-height: 1.4; }
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; margin-bottom: 6px; font-size: 0.85rem; color: #8b949e; }
        .form-group input { width: 100%; padding: 10px 12px; background: #0d1117; border: 1px solid #30363d; border-radius: 6px; color: #c9d1d9; font-size: 0.95rem; }
        .form-group input:focus { border-color: #58a6ff; outline: none; }
        .btn-submit { width: 100%; padding: 10px; background: #238636; border: none; border-radius: 6px; color: #fff; font-size: 1rem; font-weight: 600; cursor: pointer; transition: background 0.2s; }
        .btn-submit:hover { background: #2ea043; }
        .alert { padding: 10px 12px; border-radius: 6px; font-size: 0.85rem; margin-bottom: 15px; text-align: center; }
        .alert-error { background: rgba(248,81,73,0.15); border: 1px solid #f85149; color: #f85149; }
        .alert-success { background: rgba(46,160,67,0.15); border: 1px solid #2ea043; color: #3fb950; }
        .back-link { display: block; text-align: center; margin-top: 20px; font-size: 0.85rem; color: #58a6ff; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="auth-card">
    <h2>Reset Password</h2>

    <div id="alertMsg" class="alert <?php echo !$token_valid ? 'alert-error' : ''; ?>" style="<?php echo !$token_valid ? 'display:block;' : 'display:none;'; ?>">
        <?php echo htmlspecialchars($error_message); ?>
    </div>

    <?php if ($token_valid): ?>
    <form id="resetForm">
        <input type="hidden" id="token" value="<?php echo htmlspecialchars($raw_token); ?>">
        
        <div class="form-group">
            <label for="password">New Password</label>
            <input type="password" id="password" required>
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirm New Password</label>
            <input type="password" id="confirm_password" required>
        </div>

        <button type="submit" class="btn-submit">Reset Password</button>
    </form>
    <?php endif; ?>

    <a href="login.php" class="back-link">&larr; Back to Login</a>
</div>

<?php if ($token_valid): ?>
<script>
document.getElementById('resetForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const token = document.getElementById('token').value;
    const password = document.getElementById('password').value;
    const confirm = document.getElementById('confirm_password').value;
    const alertMsg = document.getElementById('alertMsg');

    alertMsg.style.display = 'none';

    try {
        const response = await fetch('reset_password.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ token: token, password: password, confirm_password: confirm })
        });

        const data = await response.json();

        if (data.status === 'success') {
            alertMsg.className = 'alert alert-success';
            alertMsg.textContent = data.message;
            alertMsg.style.display = 'block';
            document.getElementById('resetForm').style.display = 'none';
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
<?php endif; ?>

</body>
</html>