<?php
namespace App\Controllers;

use App\Services\TaskService;
use App\Repositories\TaskRepository;

class TaskController
{
    private TaskService $taskService;

    public function __construct()
    {
        $taskRepository = new TaskRepository();
        $this->taskService = new TaskService($taskRepository);
    }

    public function index(): void
    {
        $userId = $_SESSION['user_id'];

        if(!$userId) {
            header('Location: login.php');
            exit;
        }

        $tasks = $this->taskService->getTasksByUser($userId);

        $todoTasks = array_filter($tasks, fn($t) => $t->getStatus_id() === 1);
        $inProgressTasks = array_filter($tasks, fn($t) => $t->getStatus_id() === 2);
        $doneTasks = array_filter($tasks, fn($t) => $t->getStatus_id() === 3);



        require_once __DIR__ . '/../../views/dashboard.php';
    }

    public function create(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: dashboard.php');
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        $userId = $_SESSION['user_id'];

        $title = $data['title'] ?? '';
        $description = $data['description'] ?? null;
        $finish_on = $data['finish_on'] ?? null;

        $result = $this->taskService->createTask($title, $description, $finish_on, $userId);

        echo json_encode($result);
    }

    public function updateStatus(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $taskId = $data['task_id'] ?? 0;
        $statusId = $data['status_id'] ?? 0;

        $result = $this->taskService->updateTaskStatus($taskId, $statusId);

        echo json_encode($result);
    }

    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $taskId = $data['task_id'] ?? 0;

        $result = $this->taskService->deleteTask($taskId);

        echo json_encode($result);
    }

    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            exit;
        }

        // LEER JSON DEL FRONTEND
        $data = json_decode(file_get_contents("php://input"), true);

        $taskId = $data['id'] ?? 0;
        $title = $data['title'] ?? '';
        $description = $data['description'] ?? null;
        $finish_on = $data['finish_on'] ?? null;

        $result = $this->taskService->updateTask($taskId, $title, $description, $finish_on);

        echo json_encode($result);
    }
}