<?php
namespace App\Repositories;

use App\config\Database;
use App\Models\GameSession;
use PDO;

class GameRepository implements GameRepositoryInterface {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }


    public function findActiveSession(int $userId): ?GameSession {
        $stmt = $this->db->prepare("SELECT * FROM game_sessions WHERE user_id = :uid AND status = 'in_progress' ORDER BY id DESC LIMIT 1");
        $stmt->execute(['uid' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;
        return new GameSession($row);
    }
    public function save(GameSession $session): bool {
        $sql = "INSERT INTO game_sessions (user_id, pattern, current_level, strikes, status) 
                VALUES (:uid, :pattern, :lvl, :stk, :status)";
        $stmt = $this->db->prepare($sql);
        $res = $stmt->execute([
            'uid' => $session->userId,
            'pattern' => json_encode($session->pattern),
            'lvl' => $session->currentLevel,
            'stk' => $session->strikes,
            'status' => $session->status
        ]);
        if ($res) $session->id = (int)$this->db->lastInsertId();
        return $res;
    }

    public function update(GameSession $session): bool {
        $sql = "UPDATE game_sessions SET current_level = :lvl, strikes = :stk, status = :status 
                WHERE id = :id";
        return $this->db->prepare($sql)->execute([
            'lvl' => $session->currentLevel,
            'stk' => $session->strikes,
            'status' => $session->status,
            'id' => $session->id
        ]);
    }

    public function getHistory(int $userId): array {
        $stmt = $this->db->prepare("SELECT * FROM game_sessions WHERE user_id = :uid ORDER BY created_at DESC");
        $stmt->execute(['uid' => $userId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Retornamos objetos GameSession para mantener la consistencia del modelo
        return array_map(fn($row) => new GameSession($row), $rows);
    }

    public function delete(int $id, int $userId): bool {
        $stmt = $this->db->prepare("DELETE FROM game_sessions WHERE id = :id AND user_id = :uid");
        return $stmt->execute(['id' => $id, 'uid' => $userId]);
    }
}