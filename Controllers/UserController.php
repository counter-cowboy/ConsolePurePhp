<?php

namespace Controllers;

use app\UserRepositoryJson;
use app\UserRepositoryMysql;
use DB\DB;
use Pattern\Strategy;
use PDO;

class UserController
{
    public PDO $pdo;

    public function __construct()
    {
        $this->pdo = DB::getConnection();
    }

    public function index(array $envArr, $arg1 = null, $arg2 = null, $server = null)
    {
        if ($envArr[1] === 'json') {
            if (isset($arg1))
                $this->jsonAction($arg1, $arg2);
            elseif (isset($server)) {
                $this->httpAction($server, new UserRepositoryJson(), $envArr[1]);
            }
        } elseif ($envArr[1] === 'mysql') {
            if (isset($arg1)) {
                $this->mysqlAction($arg1, $arg2);
            } elseif (isset($server)) {
                $this->httpAction($server, new UserRepositoryMysql(), $envArr[1]);
            }
        }
    }

    public function jsonAction($arg1, $arg2 = null): void
    {
        if (isset($arg1)) {

            Strategy:: strategyCode(new UserRepositoryJson(), $arg1, $arg2);

        } else {
            echo "Enter a command! Input 'help' for list of available commands. \n";
        }

    }

    public function mysqlAction($arg1, $arg2 = null): void
    {
        if (isset($arg1)) {
            Strategy::strategyCode(new UserRepositoryMysql(), $arg1, $arg2);

        } else {
            echo "Enter a command! Input 'help' for list of available commands. \n";
        }
    }

    public function httpAction($server, $repository, $env)
    {
        $method = $server['REQUEST_METHOD'];
        $uri = $server['REQUEST_URI'];

        $uriArr = explode('/', trim($uri, '/'));


        switch ($uriArr[2]) {
            case 'list-users':
                $command = 'list';
                break;
            case 'create-user':
                $command = 'add';
                break;
            case 'delete-user':
                $command = 'delete';
                break;
            default:
                $command = 'help';
                break;
        }
        $id = $uriArr[3] ?? null;

        if ($method === 'GET' && $command === 'list' && $env==='mysql') {
            $stmt = $this->pdo->prepare("SELECT * FROM users");
            $stmt->execute();
            $users = $stmt->fetchAll(2);

            echo json_encode($users);

        } elseif ($method === 'POST' && isset($_POST)) {
            if (empty($_POST['name']) || empty($_POST['email'])) {
                return "Name and Email must be passed into query together";
            } else {
                $name = $this->sanitize($_POST['name']);
                $email = $this->sanitize($_POST['email']);

                $stmt = $this->pdo->prepare("INSERT INTO users(name, email) VALUES (?,?)");
                $stmt->execute([$name, $email]);

                $id = $this->pdo->lastInsertId();

                echo "User was added: id = $id, name - $name, email - $email";
            }

        } elseif ($method === 'DELETE' && $command === 'delete') {
            Strategy::strategyCode($repository, $command, $id);
        }

    }

    public function sanitize($var)
    {
        return filter_var($var, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

}