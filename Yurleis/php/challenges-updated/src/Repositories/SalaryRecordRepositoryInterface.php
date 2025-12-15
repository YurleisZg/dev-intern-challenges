<?php

namespace App\Repositories;

use App\Models\SalaryRecord;

interface SalaryRecordRepositoryInterface
{
    public function save(SalaryRecord $record): int;
    public function findById(int $id): ?SalaryRecord;
    public function findAllByUserId(int $userId): array;
    public function delete(int $id): bool;
}