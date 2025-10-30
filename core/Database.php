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
        $stm = $this->pdo->prepare($sql);
        $stm->execute($params);
        return $results = $stm->fetchAll(PDO::FETCH_ASSOC);
    }
    // execute INSERT, DELETE, UPDATE query
    public function executeQuery($sql, $params = [])
    {
        $stm = $this->pdo->prepare($sql);
        $stm->execute($params);
    }
}
