<?php

namespace app;

use Factories\Factory;
use Interfaces\UserRepositoryInterface;

require_once 'Interfaces/UserRepositoryInterface.php';

class UserRepositoryJson implements UserRepositoryInterface
{
    public string $dataFile = 'users.json';

    public function getJsonData(): array
    {
        $data = file_get_contents($this->dataFile);
        return json_decode($data, true);
    }

    public function getUsers()
    {
        $users = $this->getJsonData();

        if (!empty($users)) {

            echo "\nUser list:\n\nID---Name--------------Email\n\n";

            foreach ($users as $user) {
                echo $user['id'] . ' - '
                    . $user['name'] . ' - '
                    . $user['email'] . "\n";
            }
        } else {
            echo "No users in list";
        }
        return json_encode($users);
    }


    public function saveUsers(): void
    {
        $users = $this->getJsonData();
        $id = $this->generateId();

        $fullName =( new Factory())->userFactory()['name'];
        $email =  (new Factory())->userFactory()['email'];

        $newUser = [
            'id' => $id,
            'name' => $fullName,
            'email' => $email
        ];

        $users[] = $newUser;

        $data = json_encode($users);
        file_put_contents($this->dataFile, $data);

        echo "User added:\n
            ID = $id  name - $fullName
            email - $email";
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

                echo "User deleted - ID $id\n";
                break;
            }
        }
        if (!$isDeleted) {
            echo "User ID not found";
        }

    }
}