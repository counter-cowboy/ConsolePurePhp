<?php

namespace app;

use Interfaces\UserInterface;
use Services\Service;

require_once 'Interfaces/UserInterface.php';

class UserJson implements UserInterface
{
    public string $dataFile = 'users.json';

    public function getUsers(): void
    {
        $data = file_get_contents($this->dataFile);
        $users = json_decode($data, true);

        if (!empty($users)) {
            echo "User list:\n";
            echo "ID---Name--------------Email\n";

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
        $data = file_get_contents($this->dataFile);
        $users = json_decode($data, true);
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
    }

    public function generateId(): int
    {
        $users = $this->getUsers();

        if (empty($users)) {
            return 1;
        }
        return end($users)['id'] + 1;
    }

    public function deleteUser($id): void
    {
        $users = $this->getUsers();

        foreach ($users as $key => $user) {
            if ($user['id'] == $id) {

                unset($users[$key]);

                file_put_contents($this->dataFile, json_encode($users));

                echo "User deleted - $id\n";
                break;
            }
        }
    }
}