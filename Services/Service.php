<?php

namespace Services;

class Service
{
    public static function generateName(): string
    {
        $names = [
            'John', 'Mike', 'Sarah', 'Emily',
            'James', 'Robert', 'Mary', 'Patricia', 'Linda'
        ];
        return $names[array_rand($names)];
    }

    public static function generateLastName(): string
    {
        $lastNames = ['Smith', 'Johnson', 'Williams',
            'Jones', 'Brown', 'Davis', 'Miller', 'Wilson', 'Taylor'
        ];
        return $lastNames[array_rand($lastNames)];
    }

    public static function generateEmail($name, $lastName): string
    {
        return strtolower($name) . '.' . strtolower($lastName) . '@example.com';
    }

    public static function help(): void
    {
        echo "Commands:\n";
        echo "list - Show all users.\n";
        echo "add - Add random user to list.\n";
        echo "delete id - Delete user by ID. \n";

    }

}