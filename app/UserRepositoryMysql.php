<?php

namespace app;

use DB\DB;
use Factories\Factory;
use Interfaces\UserRepositoryInterface;
use PDO;

class UserRepositoryMysql implements UserRepositoryInterface
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
        $users = $stmt->fetchAll(2);

        if (empty($users)) {
            echo 'No users found';

        } else {
            echo "ID --Name-------------Email\n\n";

            foreach ($users as $user) {
                echo $user['id'] . ' - ' . $user['name'] . ' - ' . $user['email'] . "\n\n";
            }

        }

    }

    public function saveUsers(): void
    {
        $fullName = (new Factory())->userFactory()['name'];
        $email = (new Factory())->userFactory()['email'];

        $stmt = $this->pdo->prepare("INSERT INTO users(name, email) VALUES (?,?)");
        $stmt->execute([$fullName, $email]);

        $id = $this->pdo->lastInsertId();

        echo "User was added: id = $id, name - $fullName, email - $email";
    }

    public function deleteUser($id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM users WHERE id = ?');
        $stmt->execute([$id]);

        if ($stmt->rowCount() > 0) {
            echo "User was deleted: ID - $id, ";
        } else {
            echo 'Not User-ID found';
        }
    }
}