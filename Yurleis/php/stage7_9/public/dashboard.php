<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

use App\Controllers\TaskController;
use App\Services\AuthService;
use App\Repositories\UserRepository;

// Verificar autenticación
$authService = new AuthService(new UserRepository());
if (!$authService->isAuthenticated()) {
    header('Location: login.php');
    exit;
}

$controller = new TaskController();
$controller->index();