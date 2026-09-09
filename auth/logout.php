<?php
session_start();

// 1. Unset all session variables
$_SESSION = array();

// 2. Clear the session cookie on the user's browser
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// 3. Destroy the server-side session
session_destroy();

header('Content-Type: application/json');
echo json_encode(["status" => "success", "message" => "Logged out successfully."]);
exit();
?>