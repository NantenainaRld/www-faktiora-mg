<?php

// DEBUG for prod 
define('DEBUG', true);

// Global Database connection parameters
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'faktiora-db');
define('DB_USER', 'root');
define('DB_PASS', '');

//get protocols (https , http)
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']
    === 'on' ? 'https' : 'http';
//site url
$host  = $_SERVER['HTTP_HOST'];
//index.php path - /public/index.php
$script_dir = dirname($_SERVER['SCRIPT_NAME']);
//base path -  /public/
define('BASE_URL', rtrim($script_dir, '/'));
//site url with https - http ===  https://site.com/
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
        //redirect to error page
        header('Location: ' . SITE_URL . '/error?messages=' . __('errors.catch.database', ['field' => $e->getMessage()]));
    }
}
