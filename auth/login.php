<?php
session_start();

// Handle API Login Requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once 'db.php';

    $raw_input = file_get_contents('php://input');
    $input = json_decode($raw_input, true);
    if (!is_array($input)) {
        $input = $_POST;
    }

    $login_input = trim($input['login_input'] ?? $input['username'] ?? $input['email'] ?? '');
    $password    = $input['password'] ?? '';

    if (empty($login_input) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "Please enter both username/email and password."]);
        exit();
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username OR email = :email LIMIT 1");
        $stmt->execute([
            ':username' => $login_input,
            ':email'    => $login_input
        ]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role'] ?? 'user';

            echo json_encode([
                "status"   => "success",
                "message"  => "Login successful!",
                "username" => $user['username']
            ]);
            exit();
        }

        echo json_encode(["status" => "error", "message" => "Incorrect username/email or password."]);
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
    <title>Login - Gamora Gaming Cafe</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #0d1117; color: #c9d1d9; display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 20px; }
        .auth-card { background: #161b22; border: 1px solid #30363d; border-radius: 8px; padding: 30px; width: 100%; max-width: 400px; box-shadow: 0 8px 24px rgba(0,0,0,0.5); }
        .auth-card h2 { color: #58a6ff; font-size: 1.5rem; margin-bottom: 20px; text-align: center; }
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; margin-bottom: 6px; font-size: 0.85rem; color: #8b949e; }
        .form-group input { width: 100%; padding: 10px 12px; background: #0d1117; border: 1px solid #30363d; border-radius: 6px; color: #c9d1d9; font-size: 0.95rem; }
        .form-group input:focus { border-color: #58a6ff; outline: none; }
        .btn-submit { width: 100%; padding: 10px; background: #238636; border: none; border-radius: 6px; color: #fff; font-size: 1rem; font-weight: 600; cursor: pointer; transition: background 0.2s; }
        .btn-submit:hover { background: #2ea043; }
        .alert { padding: 10px 12px; border-radius: 6px; font-size: 0.85rem; margin-bottom: 15px; display: none; text-align: center; }
        .alert-error { background: rgba(248,81,73,0.15); border: 1px solid #f85149; color: #f85149; }
        .alert-success { background: rgba(46,160,67,0.15); border: 1px solid #2ea043; color: #3fb950; }
        .forgot-link { display: block; text-align: right; margin-top: 6px; font-size: 0.8rem; color: #58a6ff; text-decoration: none; }
        .forgot-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="auth-card">
    <h2>Login</h2>

    <div id="alertMsg" class="alert"></div>

    <form id="loginForm">
        <div class="form-group">
            <label for="login_input">Username or Gmail</label>
            <input type="text" id="login_input" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" required>
            <a href="forgot_password.php" class="forgot-link">Forgot Password?</a>
        </div>

        <button type="submit" class="btn-submit">Log In</button>
    </form>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const loginInput = document.getElementById('login_input').value;
    const password = document.getElementById('password').value;
    const alertMsg = document.getElementById('alertMsg');

    alertMsg.style.display = 'none';

    try {
       const response = await fetch('', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ login_input: loginInput, password: password })
        });

        const data = await response.json();

       if (data.status === 'success') {
            alertMsg.className = 'alert alert-success';
            alertMsg.textContent = data.message;
            alertMsg.style.display = 'block';
            setTimeout(() => {
                window.location.href = '../drpdwn/profile.php';
            }, 1000);
        
        
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