<?php

namespace App\Repositories;

use App\Models\User;

interface UserRepositoryInterface
{
    public function create(User $user): User;

    public function update(User $user): bool;

    public function delete(int $id): bool;

    public function findAll(): array;

    public function findByEmail(string $email): ?User;

    public function emailExists(string $email): bool;
}