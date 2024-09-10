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
            if (isset($arg1)) {
                $this->jsonConsoleAction($arg1, $arg2);
            } elseif (isset($server)) {
                $this->httpAction($server, new UserRepositoryJson(), $envArr[1]);
            }
        } elseif ($envArr[1] === 'mysql') {
            if (isset($arg1)) {
                $this->mysqlConsoleAction($arg1, $arg2);
            } elseif (isset($server)) {
                $this->httpAction($server, new UserRepositoryMysql(), $envArr[1]);
            }
        }
    }

    public function jsonConsoleAction($arg1, $arg2 = null): void
    {
        if (isset($arg1)) {
            Strategy:: strategyCode(new UserRepositoryJson(), $arg1, $arg2);

        } else {
            echo "Enter a command! Input 'help' for list of available commands. \n";
        }
    }

    public function mysqlConsoleAction($arg1, $arg2 = null): void
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


        if ($env === 'json') {
            if ($method === 'GET' && $command === 'list') {
                $users[]=$repository->getJsonData();

                if (!empty($users)) {
                    echo json_encode($users);

                }else{
                    http_response_code(404);
                    echo json_encode([
                        'status' => false,
                        'message' => 'No users found'
                    ]);
                }


            } elseif ($method === 'POST' ) {
                $data = json_decode(file_get_contents('php://input'), true);

                if (empty($data['name']) || empty($data['email'])) {

                    echo json_encode(['error' => 'Name and Email required']);

                } else {
                    $name = $data['name'];
                    $email = $data['email'];

                    $users = $repository->getJsonData();
                    $id = $repository->generateId();

                    $users[] = [
                        'id' => $id,
                        'name' => $name,
                        'email' => $email
                    ];
                    $data = json_encode($users);
                    file_put_contents($repository->dataFile, $data);

                    echo json_encode([
                        'status' => 'ok',
                        'user' => [
                            'id' => $id,
                            'name' => $name,
                            'email' => $email
                        ]
                    ]);
                }

            } elseif ($method === 'DELETE' && $command === 'delete') {
                Strategy::strategyCode($repository, $command, $id);
            }
        }

        elseif ($env === 'mysql') {
            if ($method === 'GET' && $command === 'list') {

                $stmt = $this->pdo->prepare("SELECT * FROM users");
                $stmt->execute();
                $users = $stmt->fetchAll(2);

                echo json_encode($users);

            } elseif ($method === 'POST' && isset($_POST) && $command==='add') {
                $data = json_decode(file_get_contents('php://input'), true);


                if (empty($data['name'] || empty($data['email']))) {
                    echo json_encode(['error' => 'Name and Email required']);
                } else {
                    $name = $this->sanitize($data['name']);
                    $email = $this->sanitize($data['email']);

                    $stmt = $this->pdo->prepare("INSERT INTO users(name, email) VALUES (?,?)");
                    $stmt->execute([$name, $email]);

                    $id = $this->pdo->lastInsertId();
                    echo json_encode([
                        'status' => 'ok',
                        'user' => [
                            'id' => $id,
                            'name' => $name,
                            'email' => $email
                        ]
                    ]);
                }

            } elseif ($method === 'DELETE' && $command === 'delete') {
                $stmt = $this->pdo->prepare('DELETE FROM users WHERE id = ?');
                $stmt->execute([$id]);

                if ($stmt->rowCount() > 0) {
                    http_response_code(200);
                    echo json_encode([
                        'status'=>'ok',
                        'message'=>"User was deleted: ID - $id"]);
                } else {
                    http_response_code(404);
                    echo json_encode([
                        'status'=>false,
                        'message'=>'Not User-ID found'
                    ] );
                }
            }
        }
    }

    public function sanitize($var)
    {
        return filter_var($var, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

}