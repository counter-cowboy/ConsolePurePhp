<?php

namespace Pattern;

require_once 'Interfaces/UserRepositoryInterface.php';
require_once 'Services/HelpCommand.php';

use Interfaces\UserRepositoryInterface;
use Services\HelpCommand;
class Strategy
{
    public static function strategyCode(UserRepositoryInterface $user, $arg1, $arg2 = null)
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
                HelpCommand::help();
                break;

            default:
                echo "Unknown command";
        }
    }
}