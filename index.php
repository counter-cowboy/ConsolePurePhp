<?php

use app\UserJson;

require_once 'app/UserJson.php';

$envArr = explode('=', file_get_contents('.env'));

if ($envArr[1] === 'json') {
    $dataFile = 'users.json';

    $userJson = new UserJson();

    if (isset($argv[1])) {
        switch ($argv[1]) {
            case 'list':
                $users = $userJson->getUsers($dataFile);

                if (!empty($users)) {
                    echo "User list:\n";

                    echo "ID---Name--------------Email\n";

                    foreach ($users as $user) {
                        echo $user['id'] . ' - ' . $user['name'] . ' - ' . $user['email'] . "\n";
                    }
                } else {
                    echo "No users in list";
                }
                break;

            case 'add':
                $users = $userJson->getUsers($dataFile);
                $id = $userJson->generateId($dataFile);
                $name = $userJson->generateName();
                $lastName = $userJson->generateLastName();
                $fullName = $name . ' ' . $lastName;
                $email = $userJson->generateEmail($name, $lastName);

                $newUser = [
                    'id' => $id,
                    'name' => $fullName,
                    'email' => $email
                ];

                $users[] = $newUser;
                $userJson->saveUsers($users, $dataFile);
                echo "User was added: $id . $name . ($email)";

                break;

            case 'delete':
                if (isset($argv[2])) {
                    $id = (int)$argv[2];
                    $users = $userJson->getUsers($dataFile);

                    foreach ($users as $key => $user) {

                        if ($user['id'] == $id) {
                            unset($users[$key]);

                            $userJson->saveUsers($users, $dataFile);
                            echo "User deleted - $id\n";
                            break;
                        }
                    }
                } else {
                    echo "User ID was not set.\n";
                }
                break;

            case 'help':
                echo "Commands:\n";
                echo "list - Show all users.\n";
                echo "add - Add random user to list.\n";
                echo "delete id - Delete user by ID. \n";

                break;

            default:
                echo "Unknown command";

        }


    } else {
        echo "Enter a command! Input 'help' for list of available commands. \n";
    }
}

