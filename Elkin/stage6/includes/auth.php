<?php
require_once __DIR__ . '/../config/DatabaseConn.php';

function registerUser($username, $email, $password) {
    $db = DatabaseConn::getInstance()->getConnection();

    $stmt = $db->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        return ["success" => false, "message" => "El correo ya está registrado"];
    }

    $hashed = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$username, $email, $hashed]);

    return ["success" => true];
}

function loginUser($email, $password) {
    $db = DatabaseConn::getInstance()->getConnection();

    $stmt = $db->prepare("SELECT user_id, password FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user['password'])) {
        return ["success" => false, "message" => "Credenciales incorrectas"];
    }

    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['isAuth'] = true;

    return ["success" => true];
}

