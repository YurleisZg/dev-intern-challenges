<?php
namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;

class AuthService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(string $name, string $email, string $password): array
    {
        // Validations
        if (empty($name) || empty($email) || empty($password)) {
            return ['success' => false, 'message' => 'All fields are required'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Invalid email'];
        }

        if (strlen($password) < 6) {
            return ['success' => false, 'message' => 'Password must be at least 6 characters'];
        }

        // Check if email already exists
        if ($this->userRepository->emailExists($email)) {
            return ['success' => false, 'message' => 'Email is already registered'];
        }

        // Create user
        $user = new User();
        $user->setId(uniqid('user_', true));
        $user->setName($name);
        $user->setEmail($email);
        $user->setPassword($password);

        if ($this->userRepository->create($user)) {
            return ['success' => true, 'message' => 'User registered successfully'];
        }

        return ['success' => false, 'message' => 'Error registering user'];
    }

    public function login(string $email, string $password): array
    {
        // Validations
        if (empty($email) || empty($password)) {
            return ['success' => false, 'message' => 'Email and password are required'];
        }

        // Find user
        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            return ['success' => false, 'message' => 'Invalid credentials'];
        }

        // Verify password
        if (!$user->verifyPassword($password)) {
            return ['success' => false, 'message' => 'Invalid credentials'];
        }

        // Start session
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['user_name'] = $user->getName();
        $_SESSION['user_email'] = $user->getEmail();

        return ['success' => true, 'message' => 'Login successful'];
    }

    public function logout(): void
    {
        session_destroy();
    }

    public function isAuthenticated(): bool
    {
        return isset($_SESSION['user_id']);
    }
}