<?php
require_once __DIR__ . '/../config/DatabaseConn.php';
require_once __DIR__ . '/../models/UserModel.php';


function registerUser($username, $email, $password) {
    return User::create($username, $email, $password);
}

function loginUser($email, $password) {
    $user = User::verifyCredentials($email, $password);

    if (!$user) {
        return ["success" => false, "message" => "Invalid Credentials"];
    }

    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['isAuth'] = true;

    return ["success" => true];
}

