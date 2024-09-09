<?php


use Controllers\UserController;


//header('Content-Type:application/json');

require_once 'app/UserRepositoryJson.php';
require_once 'app/UserRepositoryMysql.php';
require_once 'DB/DB.php';
require_once 'Interfaces/UserRepositoryInterface.php';
require_once 'Pattern/Strategy.php';
require_once 'Factories/Factory.php';
require_once 'Controllers/UserController.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$envArr = explode('=', file_get_contents('.env'));

$arg1 = $argv[1] ?? null;
$arg2 = $argv[2] ?? null;
$server = $_SERVER ?? null;

$newUser = new UserController();

$newUser->index($envArr, $arg1, $arg2, $server);



