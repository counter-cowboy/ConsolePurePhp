<?php

$host = 'localhost';
$db = 'console';
$user = 'user';
$pass = 'poiuy';
$chrs = 'utf8mb4';
$port=3306;
$dsn = "mysql:host=$host;dbname=$db;port=$port;charset=$chrs";
$opts = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE=> PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES=>false
];

try {
    $pdo = new PDO($dsn, $user, $pass, $opts);
}
catch (PDOException $exception)
{
    throw new PDOException($exception->getMessage(), (int)$exception->getCode());
}

