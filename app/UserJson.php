<?php

namespace app;

class UserJson
{
    public function getUsers($dataFile)
    {
        $data = file_get_contents($dataFile);
        return json_decode($data, true);
    }

    public function saveUsers($user, $dataFile)
    {
        $data = json_encode($user);
        file_put_contents($dataFile, $data);
    }

    public function generateId($dataFile): int
    {
        $users = $this->getUsers($dataFile);

        if (empty($users)) {
            return 1;
        }
            return end($users)['id'] + 1;
    }

    public function generateName(): string
    {
        $names = [
            'John', 'Mike', 'Sarah', 'Emily',
            'James', 'Robert', 'Mary', 'Patricia', 'Linda'
        ];
        return $names[array_rand($names)];
    }

    public function generateLastName(): string
    {
        $lastNames = ['Smith', 'Johnson', 'Williams',
            'Jones', 'Brown', 'Davis', 'Miller', 'Wilson', 'Taylor'
        ];
        return $lastNames[array_rand($lastNames)];
    }

    public function generateEmail($name, $lastName): string
    {
        return strtolower($name) . '.'. strtolower($lastName). '@example.com';
    }


}