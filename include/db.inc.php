<?php

function createConnection()
{
    $connexion = null;
    $user = 'root';
    $password = '0n1r0k0u-v1';
    $dsn = 'mysql:host=127.0.0.1;port=3306;dbname=gestion_biblioo';

    try {
        $connexion = new PDO($dsn, $user, $password);
    } catch (PDOException $e) {
        error_log($e->getMessage(), 0);
    }

    return $connexion;
}
