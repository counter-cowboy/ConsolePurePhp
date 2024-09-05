<?php

use app\UserJson;
use app\UserMysql;
use Services\Service;

header('Content-Type:application/json');

require_once 'app/UserJson.php';
require_once 'app/UserMysql.php';
require_once 'DB/DB.php';
require_once 'Services/Service.php';
require_once 'Interfaces/UserInterface.php';

$envArr = explode('=', file_get_contents('.env'));

// Когда будет вторая часть - вытащим это всё в отдельные сервисы.

$service = new Service();
$userJson = new UserJson();
$userMysql = new UserMysql();

if ($envArr[1] === 'json') {

    $dataFile = 'users.json';

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
               $userJson->saveUsers();

                break;

            case 'delete':
                if (isset($argv[2])) {
                    $id = (int)$argv[2];

                    $userJson->deleteUser($id);
                } else {
                    echo "User ID was not set.\n";
                }
                break;

            case 'help':
               Service::help();
                break;

            default:
                echo "Unknown command";
        }

    } else {
        echo "Enter a command! Input 'help' for list of available commands. \n";
    }
} /*
  * Stage 2
  *
  * MySQL
  *
  */
elseif ($envArr[1] === 'mysql') {

    if (isset($argv[1])) {
        switch ($argv[1]) {
            case 'list':
                $userMysql->getUsers();
                break;

            case 'add':
                $userMysql->saveUsers();
                break;

            case 'delete':
                if (isset($argv[2])) {
                    $id = (int)$argv[2];
                    $userMysql->deleteUser($id);
                } else {
                    echo "User ID was not set.\n";
                }
                break;

            case 'help':
                Service::help();
                break;

            default:
                echo "Unknown command";
        }

    } else {
        echo "Enter a command! Input 'help' for list of available commands. \n";
    }

}