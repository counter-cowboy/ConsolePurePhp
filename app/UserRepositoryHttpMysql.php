<?php

namespace app;

use Controllers\UserController;
use DB\DB;
use Interfaces\UserRepositoryInterface;
use PDO;

class UserRepositoryHttpMysql implements UserRepositoryInterface
{
    public PDO $pdo;
    public function __construct()
    {
        $this->pdo = DB::getConnection();
    }

    public function getUsers()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users");
        $stmt->execute();

        return $stmt->fetchAll(2);
    }

    public function deleteUser($id): bool
    {
        $stmt = $this->pdo
            ->prepare('DELETE FROM users WHERE id = ?');

        $stmt->execute([$id]);

        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function addUser($data): array
    {
        $name = $this->sanitize($data['name']);
        $email = $this->sanitize($data['email']);

        $stmt = $this->pdo->prepare("INSERT INTO users(name, email) VALUES (?,?)");
        $isAdded = $stmt->execute([$name, $email]);

        $id = $this->pdo->lastInsertId();
        if (!$isAdded) {
            return [];
        } else {
            return [
                'id' => $id,
                'name' => $name,
                'email' => $email
            ];
        }
    }

    public function sanitize($var)
    {
        return filter_var($var, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }
    public function saveUsers(): void
    {   }
}