<?php

namespace App\Repositories;

use App\Models\Task;


interface TaskRepositoryInterface
{
    public function create(Task $task): bool;

    public function update(Task $task): bool;

    public function delete(int $id): bool;

    public function findAll(): array;

    public function findByStatus(int $statusId): array;

    public function updateStatus(int $id, int $statusId): bool;

    public function findByUser(int $userId): array;

}