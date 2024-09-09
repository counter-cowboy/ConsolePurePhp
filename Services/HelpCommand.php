<?php

namespace Services;

class HelpCommand
{
    public static function help(): void
    {
        echo "Commands:\n";
        echo "list - Show all users.\n";
        echo "add - Add random user to list.\n";
        echo "delete id - Delete user by ID. \n";

    }

}