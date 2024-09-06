<?php

use app\UserJson;
use app\UserMysql;
use Pattern\Strategy;
use Services\Service;

header('Content-Type:application/json');

require_once 'app/UserJson.php';
require_once 'app/UserMysql.php';
require_once 'DB/DB.php';
require_once 'Services/Service.php';
require_once 'Interfaces/UserInterface.php';
require_once 'Pattern/Strategy.php';

$envArr = explode('=', file_get_contents('.env'));

$userJson = new UserJson();
$userMysql = new UserMysql();

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
