<?php

namespace DB;

use PDO;
use PDOException;

class DB
{
    public static function getConnection(): PDO
    {
        $user = 'user';
        $pass = 'poiuy';
        $dsn = "mysql:host=localhost;dbname=console;port=3306";
        $opts = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ];

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