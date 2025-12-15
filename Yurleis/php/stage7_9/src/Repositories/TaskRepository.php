<?php

namespace App\Repositories;

use App\Models\Task;
use App\config\Database;
use PDO;

class TaskRepository implements TaskRepositoryInterface
{
    private $connection;

    public function __construct()
    {
        $this->connection = Database::getInstance()->getConnection();
    }

    public function create(Task $task): bool
    {
    $sql = "INSERT INTO tasks (title, description, created_at, finish_on, status_id, user_id)
            VALUES (:title, :description, :created_at, :finish_on, :status_id, :user_id)";
    
    $stmt = $this->connection->prepare($sql);
    $stmt->bindValue(':user_id', $task->getUser_id());

    $stmt->bindValue(':title', $task->getTitle());
    $stmt->bindValue(':description', $task->getDescription());
    $stmt->bindValue(':created_at', $task->getCreated_at()->format('Y-m-d'));
    $stmt->bindValue(':finish_on', $task->getFinish_on() ? $task->getFinish_on()->format('Y-m-d') : null);
    $stmt->bindValue(':status_id', $task->getStatus_id());
    $stmt->bindValue(':user_id', $task->getUser_id());

    return $stmt->execute();
    }


    public function update(Task $task): bool
    {
        $sql = "UPDATE tasks 
                SET title = :title, description = :description, finish_on = :finish_on, status_id = :status_id 
                WHERE id = :id";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(':title', $task->getTitle());
        $stmt->bindValue(':description', $task->getDescription());
        $stmt->bindValue(':finish_on', 
            $task->getFinish_on() ? $task->getFinish_on()->format('Y-m-d') : null
        );

        $stmt->bindValue(':status_id', $task->getStatus_id());
        $stmt->bindValue(':id', $task->getId());
        $stmt->bindValue(':user_id', $task->getUser_id());

        return $stmt->execute();
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM tasks WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM tasks ORDER BY id DESC";
        $stmt = $this->connection->query($sql);

        $tasksData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $tasks = [];

        foreach ($tasksData as $data) {
            $task = new Task();
            $task->setId((int)$data['id']);
            $task->setTitle($data['title']);
            $task->setDescription($data['description']);
            $task->setCreated_at(new \DateTime($data['created_at']));
            $task->setFinish_on(
            $data['finish_on'] ? new \DateTime($data['finish_on']) : null );

            $task->setStatus_id((int)$data['status_id']);
            $task->setUser_id((int)$data['user_id']);
    

            $tasks[] = $task;
        }

        return $tasks;
    }

    public function findByStatus(int $statusId): array
    {
        $sql = "SELECT * FROM tasks WHERE status_id = :status_id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':status_id', $statusId);
        $stmt->execute();

        $tasksData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $tasks = [];

        foreach ($tasksData as $data) {
            $task = new Task();
            $task->setId((int)$data['id']);
            $task->setTitle($data['title']);
            $task->setDescription($data['description']);
            $task->setCreated_at(new \DateTime($data['created_at']));
            $task->setFinish_on(new \DateTime($data['finish_on']));
            $task->setStatus_id((int)$data['status_id']);

            $tasks[] = $task;
        }

        return $tasks;
    }

    public function updateStatus(int $id, int $statusId): bool
    {
        $sql = "UPDATE tasks SET status_id = :status_id WHERE id = :id";
        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(':status_id', $statusId);
        $stmt->bindValue(':id', $id);

        return $stmt->execute();
    }

    public function findByUser(int $userId): array
    {
        $sql = "SELECT * FROM tasks WHERE user_id = :user_id ORDER BY id DESC";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':user_id', $userId);
        $stmt->execute();

        $tasksData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $tasks = [];

        foreach ($tasksData as $data) {
            $task = new Task();
            $task->setId((int)$data['id']);
            $task->setTitle($data['title']);
            $task->setDescription($data['description']);
            $task->setCreated_at(new \DateTime($data['created_at']));
            $task->setFinish_on(
                $data['finish_on'] ? new \DateTime($data['finish_on']) : null
            );
            $task->setStatus_id((int)$data['status_id']);
            $task->setUser_id((int)$data['user_id']);

            $tasks[] = $task;
        }

        return $tasks;
    }
}
