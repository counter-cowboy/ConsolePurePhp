<?php

namespace Interfaces;

interface UserRepositoryInterface
{
    public function  getUsers();

    public function saveUsers();

    public function deleteUser($id);
}