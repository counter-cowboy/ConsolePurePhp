<?php

namespace Factories;

class Factory
{
    public  function userFactory(): array
    {
        $name = $this-> generateName();
        $lastName =$this->generateLastName();
        $fullName = $name . ' ' . $lastName;
        $email = $this-> generateEmail($name, $lastName);

        return ['name'=>$fullName,
            'email'=>$email];
    }

    public  function generateName(): string
    {
        $names = [
            'John', 'Mike', 'Sarah', 'Emily',
            'James', 'Robert', 'Mary', 'Patricia', 'Linda'
        ];
        return $names[array_rand($names)];
    }

    public  function generateLastName(): string
    {
        $lastNames = ['Smith', 'Johnson', 'Williams',
            'Jones', 'Brown', 'Davis', 'Miller', 'Wilson', 'Taylor'
        ];
        return $lastNames[array_rand($lastNames)];
    }

    public  function generateEmail($name, $lastName): string
    {
        return strtolower($name) . '.' . strtolower($lastName) . '@example.com';
    }

}