<?php
session_start();
header('Content-Type: application/json');
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login_input = trim($_POST['login_input'] ?? '');
    $password    = $_POST['password'] ?? '';

    if (empty($login_input) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "Please enter all fields."]);
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $login_input, $login_input);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];

            echo json_encode([
                "status" => "success",
                "message" => "Login successful!",
                "username" => $user['username']
            ]);
            exit;
        }
    }

    echo json_encode(["status" => "error", "message" => "Invalid username/email or password."]);
}
?>