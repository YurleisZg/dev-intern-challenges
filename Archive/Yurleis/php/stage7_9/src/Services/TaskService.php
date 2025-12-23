<?php
namespace App\Services;

use App\Models\Task;
use App\Repositories\TaskRepository;
use DateTime;

class TaskService
{
    private TaskRepository $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function getAllTasks(): array
    {
        return $this->taskRepository->findAll();
    }

    public function getTasksByStatus(string $status): array
    {
        $statusMap = [
            'todo' => 1,
            'in_progress' => 2,
            'done' => 3
        ];

        $statusId = $statusMap[$status] ?? 1;
        return $this->taskRepository->findByStatus($statusId);
    }

    public function createTask(string $title, ?string $description, ?string $finish_on, int $userId): array
    {
        if (empty($title)) {
            return ['success' => false, 'message' => 'El título es obligatorio'];
        }

        $task = new Task();
        $task->setTitle($title);
        $task->setDescription($description);
        $task->setCreated_at(new DateTime());

        
        if ($finish_on) {
            try {
                $task->setFinish_on(new DateTime($finish_on));
            } catch (\Exception $e) {
                return ['success' => false, 'message' => 'Fecha inválida'];
            }
        }
        $task->setUser_id($userId);
        $task->setStatus_id(1); // Created

        if ($this->taskRepository->create($task)) {
            return ['success' => true, 'message' => 'Tarea creada exitosamente'];
        }

        return ['success' => false, 'message' => 'Error al crear la tarea'];
    }

    public function updateTaskStatus(int $taskId, int $statusId): array
    {
        if ($this->taskRepository->updateStatus($taskId, $statusId)) {
            return ['success' => true, 'message' => 'Estado actualizado'];
        }

        return ['success' => false, 'message' => 'Error al actualizar estado'];
    }

    public function updateTask(int $taskId, string $title, string $description, ?string $finish_on): array
    {
        if (empty($title)) {
            return ['success' => false, 'message' => 'El título es obligatorio'];
        }

        $task = new Task();
        $task->setId($taskId);
        $task->setTitle($title);
        $task->setDescription($description);
        
        $task->setStatus_id(1);

        if ($finish_on) {
            try {
                $task->setFinish_on(new DateTime($finish_on));
            } catch (\Exception $e) {
                return ['success' => false, 'message' => 'Fecha inválida'];
            }
        } else {
            $task->setFinish_on(null);
        }

        if ($this->taskRepository->update($task)) {
            return ['success' => true, 'message' => 'Tarea actualizada exitosamente'];
        }

        return ['success' => false, 'message' => 'Error al actualizar la tarea'];
    }

    public function deleteTask(int $taskId): array
    {
        if ($this->taskRepository->delete($taskId)) {
            return ['success' => true, 'message' => 'Tarea eliminada'];
        }

        return ['success' => false, 'message' => 'Error al eliminar tarea'];
    }

    public function getTasksByUser(int $userId): array
    {
        return $this->taskRepository->findByUser($userId);
    }
}