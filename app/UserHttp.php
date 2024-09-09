<?php

namespace app;

class UserHttp
{
    public static function db()
    {
        return UserRepositoryMysql::getConnection();
    }

    public static function listUsers(): void
    {
        $pdo = self::db();
        $stmt = $pdo->prepare("SELECT * FROM users");
        $stmt->execute();
        $users = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        echo json_encode($users);
    }

    public static function createUser($input): void
    {
        $pdo = self::db();
        $data = json_decode($input, true);

        $stmt = $pdo->prepare("INSERT INTO users 
                                (name, email) VALUES  (?,?)");
        $stmt->execute([$data['name'], $data['email']]);

        echo json_encode(['success' => true]);
    }

    public static function deleteUser($id)
    {
        $pdo = self::db();

        $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
        $stmt->execute([$id]);

        echo json_encode(['success' => true]);
    }


}