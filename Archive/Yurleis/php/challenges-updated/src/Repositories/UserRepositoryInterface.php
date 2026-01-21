<?php

namespace App\Repositories;

use App\Models\Elkin\ElkinUser;

interface UserRepositoryInterface
{
    public function create(ElkinUser $user): ElkinUser;

    public function update(ElkinUser $user): bool;

    public function delete(int $id): bool;

    public function findAll(): array;

    public function findByEmail(string $email): ?ElkinUser;

    public function emailExists(string $email): bool;
}