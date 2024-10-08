<?php

namespace app;

use Factories\Factory;
use Interfaces\UserRepositoryInterface;
use Reporter\Reporter;

require_once 'Interfaces/UserRepositoryInterface.php';

class UserRepositoryJson implements UserRepositoryInterface
{
    public Factory $factory;
    public Reporter $reporter;
    public string $dataFile = 'users.json';
    public function __construct()
    {
        $this->factory=new Factory();
        $this->reporter=new Reporter();
    }

    public function getJsonData()
    {
        $data = file_get_contents($this->dataFile);
        return json_decode($data, true);
    }

    public function getUsers()
    {
        $users = $this->getJsonData();
        $this->reporter->consoleReportList($users);
    }


    public function saveUsers(): void
    {
        $users = $this->getJsonData();
        $id = $this->generateId();
        $userData=$this->factory->userFactory();

        $fullName =$userData['name'];
        $email =  $userData['email'];

        $newUser = [
            'id' => $id,
            'name' => $fullName,
            'email' => $email
        ];

        $users[] = $newUser;

        $data = json_encode($users);
        file_put_contents($this->dataFile, $data);

        $this->reporter->consoleReportAdd($newUser);

    }

    public function generateId(): int
    {
        $users = $this->getJsonData();

        if (empty($users)) {
            return 1;
        }
        return end($users)['id'] + 1;
    }

    public function deleteUser($id): void
    {
        $users = $this->getJsonData();
        $isDeleted = false;

        foreach ($users as $key => $user) {
            if ($user['id'] == $id) {
                unset($users[$key]);

                $isDeleted = file_put_contents($this->dataFile, json_encode($users));

                break;
            }
        }
        $this->reporter->consoleReportDelete($isDeleted, $id);
    }
}