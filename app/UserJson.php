<?php

namespace app;

use Interfaces\UserInterface;
use Services\Service;

require_once 'Interfaces/UserInterface.php';

class UserJson implements UserInterface
{
    public string $dataFile = 'users.json';

    public function getJsonData():array
    {
        $data = file_get_contents($this->dataFile);
        return json_decode($data, true);
    }

    public function getUsers(): void
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
    }


    public function saveUsers(): void
    {
        $users = $this->getJsonData();
        $id = $this->generateId();
        $name = Service:: generateName();
        $lastName = Service::generateLastName();
        $fullName = $name . ' ' . $lastName;
        $email = Service::generateEmail($name, $lastName);

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