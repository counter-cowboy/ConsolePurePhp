<?php

$host = 'localhost';
$data = 'console';
$user = 'user';
$pass = 'poiuy';
$chrs = 'utf8mb4';
$attr = "mysql:host=$host;dbname=$data;charset=$chrs";
$opts = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE=> PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES=>false
];

try {
    $pdo = new PDO($attr, $user, $pass, $opts);
}
catch (PDOException $exception)
{
    throw new PDOException($exception->getMessage(), (int)$exception->getCode());
}

