<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

use App\config\Database;
use App\Controllers\SalaryController;
use App\Services\AuthService;
use App\Repositories\UserRepository;

// 1. Security Verification
$authService = new AuthService(new UserRepository());
if (!$authService->isAuthenticated()) {
    header('Location: login.php');
    exit;
}

// 2. Determine action (Salary by default)
$action = $_GET['action'] ?? 'salary';
$database = Database::getInstance();

try {
    switch ($action) {
        case 'salary':
        case 'view':  
        case 'delete': 
            $controller = new SalaryController($database);
            
            // Modify controller so index() RETURNS the HTML
            if (isset($_GET['action']) && $_GET['action'] === 'view') {
                $controller->viewRecord((int)$_GET['id']);
            } elseif (isset($_GET['action']) && $_GET['action'] === 'delete') {
                $controller->deleteRecord((int)$_GET['id']);
            } else {
                $content = $controller->index();
            }
            $activeItem = 'salary';
            break;

            case 'pattern_game':
            $gameCtrl = new \App\Controllers\GameController();
            $step = $_GET['step'] ?? 'start';
            
            if ($step === 'stage1') {
                $content = $gameCtrl->handleStage1();
            } elseif ($step === 'stage2') {
                $content = $gameCtrl->handleStage2();
            } else {
                $content = $gameCtrl->index();
            }
            $activeItem = 'pattern_game';
            break;
        default:
            $content = "<h2>Page not found</h2>";
            $activeItem = '';
    }
    
} catch (\Exception $e) {
    $content = "Error: " . $e->getMessage();
}

require __DIR__ . '/../views/dashboard.php';