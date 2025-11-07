<?php

// DEBUG for prod 
define('DEBUG', true);

// Global Database connection parameters
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'php-cash-management-db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Other global parameters
define('SITE_NAME', 'Gestion de Caisse');

// dynamic URL
//-- https or http
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']
    === 'on' ? 'https' : 'http';
//-- site.com
$host  = $_SERVER['HTTP_HOST'];
//-- /public/index.php
$script_dir = dirname($_SERVER['SCRIPT_NAME']);
//-- /public/
define('BASE_URL', rtrim($script_dir, '/'));
//-- https://site.com/
define('SITE_URL', $protocol . '://' . $host);

// Function to get a database connection using PDO
function getConnection()
{
    try {
        // data source name
        $dsn = 'mysql:host='
            . DB_HOST . ';dbname=' . DB_NAME
            . ';charset=utf8';
        // pdo instance
        $pdo = new PDO($dsn, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
    } catch (PDOException $e) {
        //dev error
        if (!DEBUG) {
            die("Database connection error : " . $e->getMessage());
        }
        //prod error
        else {
            echo "Erreur de connection à la base des données, retourner .";
            exit;
        }
    }
}
