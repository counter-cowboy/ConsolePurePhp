<?php

namespace app;

use DB\DB;
use Interfaces\UserInterface;
use Services\Service;


class UserMysql implements UserInterface
{
    public function getUsers(): void
    {
        $pdo = DB::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM users");
        $stmt->execute();
        $users = $stmt->fetchAll(2);

        if (!empty($users)) {
            echo "ID --Name-------------Email\n\n";

            foreach ($users as $user) {
                echo $user['id'] . ' - ' . $user['name'] . ' - ' . $user['email']."\n\n";
            }
        } else {
            echo 'No users found';
        }
    }

    public function saveUsers(): void
    {
        $pdo = DB::getConnection();

        $name =Service:: generateName();
        $lastName =Service::generateLastName();
        $fullName = $name . ' ' . $lastName;
        $email = Service::generateEmail($name, $lastName);

        $stmt = $pdo->prepare("INSERT INTO users(name, email) VALUES (?,?)");
        $stmt->execute([$fullName, $email]);

        $id = $pdo->lastInsertId();

        echo "User was added: id = $id, name - $name, email - $email";
    }

    public function deleteUser($id): void
    {
        $pdo = DB::getConnection();

        $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
        $stmt->execute([$id]);

        $rowCount = $stmt->rowCount();

        if ($rowCount > 0)
            echo "User was deleted: ID - $id, ";
        else
            echo 'Not User-ID found';
    }


}