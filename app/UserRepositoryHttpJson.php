<?php

namespace app;

use Factories\Factory;
use Interfaces\UserRepositoryInterface;

class UserRepositoryHttpJson implements UserRepositoryInterface
{
    public string $dataFile = 'users.json';

    public function getJsonData(): array
    {
        $data = file_get_contents($this->dataFile);
        return json_decode($data, true);
    }

    public function getUsers(): array
    {
        return $this->getJsonData();
    }


    public function saveUsers(): array
    {
        $users = $this->getJsonData();
        $id = $this->generateId();

        $fullName = (new Factory())->userFactory()['name'];
        $email = (new Factory())->userFactory()['email'];

        $users[] = [
            'id' => $id,
            'name' => $fullName,
            'email' => $email
        ];

        $users = array_keys($users);

        $data = json_encode($users);
        file_put_contents($this->dataFile, $data);

        return $users;
    }

    public function addUser($data): array
    {
        $name = $data['name'];
        $email = $data['email'];

        $users = $this->getUsers();
        $id = $this->generateId();

       $user = [
            'id' => $id,
            'name' => $name,
            'email' => $email
        ];
       $users[]=$user;
        $data = json_encode($users);
        file_put_contents($this->dataFile, $data);
        return $user;
    }

    public function generateId(): int
    {
        $users = $this->getJsonData();

        if (empty($users)) {
            return 1;
        }
        return end($users)['id'] + 1;
    }

    public function deleteUser($id)
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
        if (!$isDeleted) {
            echo "User ID not found";
        }
        return $isDeleted;
    }
}