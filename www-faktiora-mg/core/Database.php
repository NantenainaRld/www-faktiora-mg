<?php

// CLASS database : open connection - execute query
class Database
{
    private static $pdo;

    public function __construct()
    {
        //open connection
        self::$pdo = self::getConnection();
        //set time_zone
        $time_zone = (new DateTime('now', new DateTimeZone(TIME_ZONE)))->format('P');
        self::$pdo->exec("SET time_zone = '$time_zone'");
    }

    //open connection
    private static function getConnection()
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
            error_log($e->getMessage());

            //redirect to error page
            header('Location: ' . SITE_URL . '/error?messages=' . __('errors.catch.database', ['field' => $e->getMessage()]));

            return;
        }
    }

    // execute SELECT query and return result
    protected static function selectQuery($sql, $params = [])
    {
        try {
            self::$pdo = self::getConnection();
            $stm = self::$pdo->prepare($sql);
            $stm->execute($params);

            $results = $stm->fetchAll(PDO::FETCH_ASSOC);
            return [
                'message_type' => 'success',
                'data' => $results,
                'message' => 'success'
            ];
        } catch (PDOException $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            return [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.query',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];
        }
    }
    // execute INSERT, DELETE, UPDATE query
    protected  static function executeQuery($sql, $params = [])
    {
        try {
            self::$pdo = self::getConnection();
            $stm = self::$pdo->prepare($sql);
            $stm->execute($params);
            return [
                'message_type' => 'success',
                'message' => 'success',
                'row_count' => $stm->rowCount()
            ];
        } catch (PDOException $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            return [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.query',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];
        }
    }
}
