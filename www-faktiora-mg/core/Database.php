<?php

// CLASS database : open connection - execute query
class Database
{
    private static $pdo;

    public function __construct()
    {
        // open  (config.php)
        self::$pdo = getConnection();
        //set time_zone
        $time_zone = (new DateTime('now', new DateTimeZone(TIME_ZONE)))->format('P');
        self::$pdo->exec("SET time_zone = '$time_zone'");
    }

    // execute SELECT query and return result
    protected static function selectQuery($sql, $params = [])
    {
        try {
            $stm = self::$pdo->prepare($sql);
            $stm->execute($params);

            $results = $stm->fetchAll(PDO::FETCH_ASSOC);
            return [
                'message_type' => 'success',
                'data' => $results,
                'message' => 'success'
            ];
        } catch (PDOException $e) {
            error_log($e->getMessage());

            return [
                'message_type' => 'error',
                'message' => __('errors.catch.query', ['field' => $e->getMessage()])
            ];
        }
    }
    // execute INSERT, DELETE, UPDATE query
    protected  static function executeQuery($sql, $params = [])
    {
        try {
            $stm = self::$pdo->prepare($sql);
            $stm->execute($params);
            return ['message_type' => 'success', 'message' => 'success', 'row_count' => $stm->rowCount()];
        } catch (PDOException $e) {
            error_log($e->getMessage());

            return [
                'message_type' => 'error',
                'message' => __('errors.catch.query', ['field' => $e->getMessage()])
            ];
        }
    }
}
