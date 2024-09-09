<?php

use app\UserRepositoryJson;
use app\UserRepositoryMysql;
use Pattern\Strategy;

header('Content-Type:application/json');

require_once 'app/UserRepositoryJson.php';
require_once 'app/UserRepositoryMysql.php';
require_once 'DB/DB.php';
require_once 'Interfaces/UserRepositoryInterface.php';
require_once 'Pattern/Strategy.php';
require_once 'Factories/Factory.php';

$envArr = explode('=', file_get_contents('.env'));

$userJson = new UserRepositoryJson();
$userMysql = new UserRepositoryMysql();

if ($envArr[1] === 'json') {

    if (isset($argv[1])) {

        $arg2 = $argv[2] ?? null;

        Strategy:: strategyCode($userJson, $argv[1], $arg2);

    } else {
        echo "Enter a command! Input 'help' for list of available commands. \n";
    }
} elseif ($envArr[1] === 'mysql') {

    if (isset($argv[1])) {

        $arg2 = $argv[2] ?? null;

        Strategy::strategyCode($userMysql, $argv[1], $arg2);

    } else {
        echo "Enter a command! Input 'help' for list of available commands. \n";
    }


}
