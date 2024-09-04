<?php

namespace app;

use http\Exception;
use PDO;
use PDOException;

class UserMysql
{
    public $user = 'user';
    public $pass = 'poiuy';
    public $dsn = "mysql:host='localhost';dbname='console;port=3306";
    public $opts = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ];

    public function getUsers(): void
    {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM users");
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
        $pdo = self::getConnection();

        $name = $this->generateName();
        $lastName = $this->generateLastName();
        $fullName = $name . ' ' . $lastName;
        $email = $this->generateEmail($name, $lastName);

        $stmt = $pdo->prepare("INSERT INTO users(name, email) VALUES (?,?)");
        $stmt->execute([$fullName, $email]);

        $id = $pdo->lastInsertId();

        echo "User was added: id = $id, name - $name, email - $email";
    }

    public function deleteUser($id): void
    {
        $pdo = self::getConnection();

        $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
        $stmt->execute([$id]);

        $rowCount = $stmt->rowCount();

        if ($rowCount > 0)
            echo "User was deleted: ID - $id, ";
        else
            echo 'Not User-ID found';
    }

    public function generateName(): string
    {
        $names = [
            'John', 'Mike', 'Sarah', 'Emily',
            'James', 'Robert', 'Mary', 'Patricia', 'Linda'
        ];
        return $names[array_rand($names)];
    }

    public function generateLastName(): string
    {
        $lastNames = ['Smith', 'Johnson', 'Williams',
            'Jones', 'Brown', 'Davis', 'Miller', 'Wilson', 'Taylor'
        ];
        return $lastNames[array_rand($lastNames)];
    }

    public function generateEmail($name, $lastName): string
    {
        return strtolower($name) . '.' . strtolower($lastName) . '@example.com';
    }


    public static function getConnection(): PDO
    {
        global $user, $pass, $dsn, $opts;

        try {
            $pdo = new PDO($dsn, $user, $pass, $opts);

            $pdo->exec("CREATE TABLE IF NOT EXISTS users (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(255) NOT NULL,
                    email VARCHAR(255) NOT NULL 
                  )");

        } catch (PDOException $exception) {
            throw new PDOException($exception->getMessage(), (int)$exception->getCode());
        }

        return $pdo;
    }


}