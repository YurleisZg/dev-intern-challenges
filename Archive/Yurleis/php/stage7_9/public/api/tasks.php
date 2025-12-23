<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();


use App\Controllers\TaskController;
use App\Services\AuthService;
use App\Repositories\UserRepository;

header('Content-Type: application/json');

// Verificar autenticación
$authService = new AuthService(new UserRepository());
if (!$authService->isAuthenticated()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'No autenticado']);
    exit;
}

$controller = new TaskController();

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'create':
        $controller->create();
        break;
    case 'update':
        $controller->update();
        break;
    case 'update-status':
        $controller->updateStatus();
        break;
    case 'delete':
        $controller->delete();
        break;
    default:
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Acción no válida']);
}