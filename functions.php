<?php

function getDatabaseConnection()
{
    $dsn = 'mysql:dbname=2110database;host=localhost';
    $username = '2110';
    $password = 'pass';

    try {
        $connection = new PDO($dsn, $username, $password);
    }
    catch (PDOException $exception) {
        $errorMessage = $exception->getMessage();
        echo $errorMessage;
        exit;
    }

    return $connection;
}
