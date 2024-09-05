<?php

namespace Interfaces;

interface UserInterface
{
    public function  getUsers();

    public function saveUsers();
    public function deleteUser($id);
}