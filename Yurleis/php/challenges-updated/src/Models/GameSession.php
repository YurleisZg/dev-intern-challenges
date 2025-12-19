<?php
namespace App\Models;

class GameSession {
    public ?int $id = null;
    public int $userId;
    public array $pattern = [];
    public int $currentLevel = 1;
    public int $strikes = 0;
    public string $status = 'in_progress';

    public function __construct(array $data = []) {
        $this->id = $data['id'] ?? null;
        $this->userId = $data['user_id'] ?? 0;
        $this->pattern = is_string($data['pattern'] ?? null) ? json_decode($data['pattern'], true) : ($data['pattern'] ?? []);
        $this->currentLevel = $data['current_level'] ?? 1;
        $this->strikes = $data['strikes'] ?? 0;
        $this->status = $data['status'] ?? 'in_progress';
    }

    public function isFinished(): bool {
        return $this->status !== 'in_progress';
    }
}