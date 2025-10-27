<?php

// Global Database connection parameters
define('DB_HOST', 'LOCALHOST');
define('DB_NAME', 'php-cash-management-db');
define('DB_USER', 'root');
define('DB_PASS', '');
// Other global parameters
define('SITE_NAME', 'Gestion de Caisse');
// For prod or dev
define('DEBUG', true);

// Function to get a database connection using PDO
function getConnection()
{
    try {
        // data source name
        $dsn = 'mysql:host='
        . DB_HOST .';dbname=' . DB_NAME
        . ';charset=utf8';
        // pdo instance
        $pdo = new PDO($dsn, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
    } catch (PDOException $e) {
        if (DEBUG) {
            die('DB error :' . $e->getMessage());
        } else {
            die('Connection error .');
        }
    }
}
