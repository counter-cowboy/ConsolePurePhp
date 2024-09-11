<?php

namespace Controllers;

use app\UserRepositoryHttpJson;
use app\UserRepositoryHttpMysql;
use app\UserRepositoryJson;
use app\UserRepositoryMysql;
use DB\DB;
use Pattern\Strategy;
use PDO;
use Reporter\Reporter;

class UserController
{
    public Reporter $reporter;

    public function __construct()
    {
        $this->reporter=new Reporter ();
    }

    public function index(array $envArr, $arg1 = null, $arg2 = null, $server = null)
    {
        if ($envArr[1] === 'json') {
            if (isset($arg1)) {
                Strategy:: strategyCode(new UserRepositoryJson(), $arg1, $arg2);

            } elseif (isset($server)) {
                $this->httpAction($server, new UserRepositoryHttpJson(), $envArr[1]);

            }
        } elseif ($envArr[1] === 'mysql') {
            if (isset($arg1)) {
                Strategy::strategyCode(new UserRepositoryMysql(), $arg1, $arg2);

            } elseif (isset($server)) {
                $this->httpAction($server, new UserRepositoryHttpMysql(), $envArr[1]);
            }
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
                $users = $repository->getUsers();

              $this->reporter->httpReportList($users);


            } elseif ($method === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);

                if (empty($data['name']) || empty($data['email'])) {
                    http_response_code(400);
                    echo json_encode(['status'=>false,
                        'error' => 'Name and Email required']);

                } else {
                    $user=$repository->addUser($data);
                  $this->reporter->httpReportAdd($user);

                }

            } elseif ($method === 'DELETE' && $command === 'delete') {
                $isDeleted= $repository->deleteUser($id);
                $this->reporter->httpReportDelete($isDeleted, $id);
            }
        }
        //MySQL working

        elseif ($env === 'mysql') {
            if ($method === 'GET' && $command === 'list') {

                $users=$repository->getUsers();
                $this->reporter->httpReportList($users);


            }
            elseif ($method === 'POST' && isset($_POST) && $command === 'add') {
                $data = json_decode(file_get_contents('php://input'), true);

                if (empty($data['name'] || empty($data['email']))) {
                    $this->reporter->badQuery();

                } else {
                    $user = $repository->addUser($data);
                   $this->reporter->httpReportAdd($user);
                }

            } elseif ($method === 'DELETE' && $command === 'delete') {
               $isDeleted= $repository->deleteUser($id);

              $this->reporter->httpReportDelete($isDeleted, $id);

            }
        }
    }

    public function sanitize($var)
    {
        return filter_var($var, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

}