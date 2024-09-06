<?php

namespace app;

use DB\DB;
use Interfaces\UserInterface;
use PDO;
use Services\Service;

class UserMysql implements UserInterface
{
    public PDO $pdo;

    public function __construct()
    {
        $this->pdo = DB::getConnection();
    }

    public function getUsers(): void
    {
//        $pdo = DB::getConnection();

        $stmt = $this->pdo->prepare("SELECT * FROM users");
        $stmt->execute();
        $users = $stmt->fetchAll(2);

        if (!empty($users)) {
            echo "ID --Name-------------Email\n\n";

            foreach ($users as $user) {
                echo $user['id'] . ' - ' . $user['name'] . ' - ' . $user['email'] . "\n\n";
            }
        } else {
            echo 'No users found';
        }
    }

    public function saveUsers(): void
    {
        $name = Service:: generateName();
        $lastName = Service::generateLastName();
        $fullName = $name . ' ' . $lastName;
        $email = Service::generateEmail($name, $lastName);

        $stmt = $this->pdo->prepare("INSERT INTO users(name, email) VALUES (?,?)");
        $stmt->execute([$fullName, $email]);

        $id = $this->pdo->lastInsertId();

        echo "User was added: id = $id, name - $name, email - $email";
    }

    public function deleteUser($id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM users WHERE id = ?');
        $stmt->execute([$id]);

        $rowCount = $stmt->rowCount();

        if ($rowCount > 0)
            echo "User was deleted: ID - $id, ";
        else
            echo 'Not User-ID found';
    }
}