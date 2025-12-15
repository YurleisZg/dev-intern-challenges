<?php

namespace App\Repositories;

use App\Models\User;
use App\config\Database;
use PDO;
use PDOException;

class UserRepository implements UserRepositoryInterface
{
    private $connection;

    public function __construct()
    {
        $this->connection = Database::getInstance()->getConnection();
    }

    public function create(User $user): User
    {
        $sql = "INSERT INTO users ( name, email, password) VALUES (:name, :email, :password)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':name', $user->getName());
        $stmt->bindValue(':email', $user->getEmail());
        $stmt->bindValue(':password', $user->getPassword());
        $stmt->execute();
        $user->setId((int)$this->connection->lastInsertId());
        return $user;
    }

    public function update(User $user): bool
    {
        $sql = "UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':name', $user->getName());
        $stmt->bindValue(':email', $user->getEmail());
        $stmt->bindValue(':password', $user->getPassword());
        $stmt->bindValue(':id', $user->getId(), PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM users";
        $stmt = $this->connection->query($sql);
        $usersData = $stmt->fetchAll();
        
        $users = [];
        foreach ($usersData as $data) {
            $user = new User((int)$data['id'], $data['name'], $data['email'], $data['password']);
            $users[] = $user;
        }
        return $users;
    }

    public function findByEmail(string $email): ?User
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $data = $stmt->fetch();

        if ($data) {
            return new User((int)$data['id'], $data['name'], $data['email'], $data['password']);
        }
        return null;
    }

    public function emailExists(string $email): bool
    {
        $sql = "SELECT COUNT(*) FROM users WHERE email = :email";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
}