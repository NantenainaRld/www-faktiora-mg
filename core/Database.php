<?php

// CLASS database : open connection - execute query
class Database
{
    private $pdo;

    public function __construct()
    {
        // open  (config.php)
        $this->pdo = getConnection();
    }

    // execute SELECT query and return result
    protected function selectQuery($sql, $params = [])
    {
        try {
            $stm = $this->pdo->prepare($sql);
            $stm->execute($params);

            $results = $stm->fetchAll(PDO::FETCH_ASSOC);
            return [
                'message_type' => 'success',
                'data' => $results,
                'message' => 'success'
            ];
        } catch (PDOException $e) {
            return [
                'message_type' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
    // execute INSERT, DELETE, UPDATE query
    public function executeQuery($sql, $params = [])
    {
        try {
            $stm = $this->pdo->prepare($sql);
            $stm->execute($params);
            return ['message_type' => 'success', 'message' => 'success'];
        } catch (PDOException $e) {
            return [
                'message_type' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
}
