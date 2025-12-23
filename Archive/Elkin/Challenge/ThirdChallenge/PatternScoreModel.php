<?php
require_once __DIR__ . '/config/DatabaseConn.php';

class PatternScoreModel {
    private $pdo;

    public function __construct() {
        $this->pdo = DatabaseConn::getInstance()->getConnection();
    }


    public function saveScore($username, $score, $time_seconds) {
        // Verificar si el usuario ya existe
        $checkStmt = $this->pdo->prepare("SELECT id, score FROM pattern_scores WHERE username = ?");
        $checkStmt->execute([$username]);
        $existingRecord = $checkStmt->fetch();

        if ($existingRecord) {
            // Si existe, actualizar SOLO si el nuevo puntaje es mayor
            if ($score > $existingRecord['score']) {
                $updateStmt = $this->pdo->prepare("UPDATE pattern_scores SET score = ?, time_seconds = ?, created_at = CURRENT_TIMESTAMP WHERE username = ?");
                return $updateStmt->execute([$score, $time_seconds, $username]);
            }
            // Si el puntaje no es mayor, retornar false (no se actualiza)
            return false;
        } else {
            // Si no existe, insertar nuevo registro
            $stmt = $this->pdo->prepare("INSERT INTO pattern_scores (username, score, time_seconds) VALUES (?, ?, ?)");
            return $stmt->execute([$username, $score, $time_seconds]);
        }
    }

    public function getTopScores($limit = 10) {
        $stmt = $this->pdo->prepare("SELECT username, score, time_seconds, created_at FROM pattern_scores ORDER BY score DESC, time_seconds ASC LIMIT :limit");
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getUserScore($username) {
        $stmt = $this->pdo->prepare("SELECT score, time_seconds, created_at FROM pattern_scores WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }
}
