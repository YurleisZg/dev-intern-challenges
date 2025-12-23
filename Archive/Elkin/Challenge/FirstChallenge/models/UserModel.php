<?php
class User {

    public function getId() {}
    public static function findByEmail(string $email): ?array {
        $db = DatabaseConn::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT user_id, username, email, password_hash FROM users WHERE email = ?");
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
        $stmt = $db->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $hashed]);

        return ["success" => true, "user_id" => (int)$db->lastInsertId()];
    }

    public static function verifyCredentials(string $email, string $password): ?array {
        $user = self::findByEmail($email);
        if (!$user) {
            return null;
        }

        if (!password_verify($password, $user['password_hash'])) {
            return null;
        }

        return $user;
    }

}