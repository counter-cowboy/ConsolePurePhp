<?php

namespace Reporter;

class Reporter
{
    public function httpReportList($users): void
    {
        if (!empty($users)) {
            http_response_code(200);
            echo json_encode($users);

        } else {
            http_response_code(404);
            echo json_encode([
                'status' => false,
                'message' => 'No users found'
            ]);
        }
    }

    public function httpReportAdd($user): void
    {
        if (!empty($user)) {
            http_response_code(200);
            echo json_encode([
                'status' => 'ok',
                'user' => $user
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'status' => false,
                'message' => 'Probably user was not added',
            ]);
        }
    }

    public function httpReportDelete($isDeleted, $id): void
    {
        if (!$isDeleted) {
            http_response_code(404);
            echo json_encode([
                'status' => false,
                'message' => "User $id not found"]);
        } else {
            http_response_code(200);
            echo json_encode([
                'status' => 'ok',
                'message' => "User ID $id was deleted"
            ]);
        }
    }

    public function badQuery()
    {
        http_response_code(400);
        echo json_encode(['error' => 'Name and Email required']);
    }

    public function consoleReportList($users)
    {
        if (!empty($users)) {

            echo "\nUser list:\n\nID---Name--------------Email\n\n";

            foreach ($users as $user) {
                echo $user['id'] . ' - '
                    . $user['name'] . ' - '
                    . $user['email'] . "\n";
            }
        } else {
            echo "No users in list";
        }
    }

    public function consoleReportAdd($user)
    {
        if (!empty($user)) {
            echo "User added:\n
            ID =" . $user['id'] . "  name - " . $user['name'] .
                "email - " . $user['email'];
        }else{
            echo "User was not added, server problem.";
        }
    }

    public function consoleReportDelete($isDeleted, $id): void
    {
        if ($isDeleted) {
            echo "User deleted - ID $id\n";
        }
        else {
            echo "User $id not found";
        }
    }
}