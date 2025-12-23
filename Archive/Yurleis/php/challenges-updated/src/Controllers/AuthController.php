<?php
namespace App\Controllers;

use App\Services\AuthService;
use App\Repositories\UserRepository;

class AuthController
{
    private AuthService $authService;

    public function __construct()
    {
        $userRepository = new UserRepository();
        $this->authService = new AuthService($userRepository);
    }

  public function showLogin(): void
{
    if ($this->authService->isAuthenticated()) {
        // Asegúrate de que este nombre coincida con tu archivo en la carpeta public
        header('Location: index.php'); 
        exit;
    }
    // Verifica que esta ruta llegue a views/login.php
    require_once __DIR__ . '/../../views/login.php';
}

    public function showRegister(): void
    {
        if ($this->authService->isAuthenticated()) {
            header('Location: index.php');
            exit;
        }
        require_once __DIR__ . '/../../views/register.php';
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: login.php');
            exit;
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $result = $this->authService->login($email, $password);

        if ($result['success']) {
            header('Location: index.php');
            exit;
        }

        $_SESSION['error'] = $result['message'];
        header('Location: login.php');
        exit;
    }

    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: register.php');
            exit;
        }

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $result = $this->authService->register($name, $email, $password);

        if ($result['success']) {
            $_SESSION['success'] = $result['message'];
            header('Location: login.php');
            exit;
        }

        $_SESSION['error'] = $result['message'];
        header('Location: register.php');
        exit;
    }

    public function logout(): void
    {
        $this->authService->logout();
        header('Location: login.php');
        exit;
    }
}