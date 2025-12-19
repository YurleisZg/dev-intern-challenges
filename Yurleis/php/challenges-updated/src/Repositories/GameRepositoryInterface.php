<?php
namespace App\Repositories;

use App\Models\GameSession;

interface GameRepositoryInterface {
    public function findActiveSession(int $userId): ?GameSession;
    public function save(GameSession $session): bool;
    public function update(GameSession $session): bool;
    public function getHistory(int $userId): array;
    public function delete(int $id, int $userId): bool;
}