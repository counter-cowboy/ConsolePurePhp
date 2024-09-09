<?php

namespace Factories;

use Services\Service;

class Factory
{
    public static function userFactory(): array
    {
        $name = Service:: generateName();
        $lastName = Service::generateLastName();
        $fullName = $name . ' ' . $lastName;
        $email = Service::generateEmail($name, $lastName);

        return ['name'=>$fullName,
            'email'=>$email];

    }

}