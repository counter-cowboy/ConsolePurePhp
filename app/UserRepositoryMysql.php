<?php

namespace app;

use DB\DB;
use Factories\Factory;
use Interfaces\UserRepositoryInterface;
use PDO;
use Reporter\Reporter;

class UserRepositoryMysql implements UserRepositoryInterface
{
    public Reporter $reporter;
    public PDO $pdo;
    public Factory $factory;

    public function __construct()
    {
        $this->reporter = new Reporter();
        $this->pdo = DB::getConnection();
        $this->factory=new Factory();
    }

    public function getUsers()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users");

        $stmt->execute();
        $this->reporter->consoleReportList($stmt->fetchAll(2));
    }

    public function saveUsers(): void
    {
        $fullName = $this->factory->userFactory()['name'];
        $email = $this->factory->userFactory()['email'];

        $stmt = $this->pdo
            ->prepare("INSERT INTO users(name, email) VALUES (?,?)");
        $isAdded = $stmt->execute([$fullName, $email]);

        $id = $this->pdo->lastInsertId();

        if ($isAdded) {
            $this->reporter->consoleReportAdd([
                'id' => $id,
                'name' => $fullName,
                'email' => $email
            ]);
        } else {
            $this->reporter->consoleReportAdd([]);
        }
    }

    public function deleteUser($id): void
    {
        $stmt = $this->pdo
            ->prepare('DELETE FROM users WHERE id = ?');
        $stmt->execute([$id]);

        if ($stmt->rowCount() > 0) {
            $this->reporter->consoleReportDelete(true, $id);
        } else {
            $this->reporter->consoleReportDelete(false, $id);
        }
    }
}