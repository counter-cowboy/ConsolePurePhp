<?php

namespace Pattern;

require_once 'Interfaces/UserInterface.php';
require_once 'Services/Service.php';

use Interfaces\UserInterface;
use Services\Service;

class Strategy
{
    public static function strategyCode(UserInterface $user, $arg1, $arg2 = null): void
    {
        switch ($arg1) {
            case 'list':
                $user->getUsers();
                break;

            case 'add':
                $user->saveUsers();
                break;

            case 'delete':
                if (!is_null($arg2)) {
                    $id = (int)$arg2;
                    $user->deleteUser($id);
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
    }
}