<?php

require_once __DIR__ . '/../config/DatabaseConn.php';

class User {
    public static function findByEmail(string $email): ?array {
        $db = DatabaseConn::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT user_id, username, email, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public static function create(string $username, string $email, string $password): array {
        $db = DatabaseConn::getInstance()->getConnection();

        $existing = self::findByEmail($email);
        if ($existing) {
            return ["success" => false, "message" => "The email is already registered."];
        }

        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $hashed]);

        return ["success" => true, "user_id" => (int)$db->lastInsertId()];
    }

    public static function verifyCredentials(string $email, string $password): ?array {
        $user = self::findByEmail($email);
        if (!$user) {
            return null;
        }

        if (!password_verify($password, $user['password'])) {
            return null;
        }

        return $user;
    }
}

class Task {
    public static function countByUser(int $userId): int {
        $db = DatabaseConn::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT COUNT(*) FROM todos WHERE user_id = ?");
        $stmt->execute([$userId]);
        return (int)$stmt->fetchColumn();
    }

    public static function listByUser(int $userId, int $start = 0, int $limit = 5): array {
        $db = DatabaseConn::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM todos WHERE user_id = :user_id ORDER BY id ASC LIMIT :start, :limit");
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':start', $start, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create(int $userId, string $title, string $description, string $state, ?string $dueDate, string $priority): int {
        $db = DatabaseConn::getInstance()->getConnection();
        $stmt = $db->prepare('INSERT INTO todos (user_id, title, description, state, due_date, priority) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$userId, $title, $description, $state, $dueDate, $priority]);
        return (int)$db->lastInsertId();
    }

    public static function findByIdForUser(int $id, int $userId): ?array {
        $db = DatabaseConn::getInstance()->getConnection();
        $stmt = $db->prepare('SELECT * FROM todos WHERE id = ? AND user_id = ?');
        $stmt->execute([$id, $userId]);
        $todo = $stmt->fetch(PDO::FETCH_ASSOC);
        return $todo ?: null;
    }

    public static function updateForUser(int $id, int $userId, array $data): bool {
        $db = DatabaseConn::getInstance()->getConnection();
        $stmt = $db->prepare('UPDATE todos SET title = ?, description = ?, state = ?, due_date = ?, priority = ? WHERE id = ? AND user_id = ?');
        $stmt->execute([
            $data['title'],
            $data['description'],
            $data['state'],
            $data['due_date'],
            $data['priority'],
            $id,
            $userId
        ]);
        return $stmt->rowCount() > 0;
    }

    public static function deleteForUser(int $id, int $userId): bool {
        $db = DatabaseConn::getInstance()->getConnection();
        $stmt = $db->prepare('DELETE FROM todos WHERE id = ? AND user_id = ?');
        $stmt->execute([$id, $userId]);
        return $stmt->rowCount() > 0;
    }
}

