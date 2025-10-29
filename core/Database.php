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
            return ['message' => 'success', 'data' => $results];
        } catch (PDOException $e) {
            return ['message' => $e->getMessage()];
        }
    }
    // execute INSERT, DELETE, UPDATE query
    public function execute($sql, $params = [])
    {
        try {
            $stm = $this->pdo->prepare($sql);
            return $stm->execute($params);
        } catch (PDOException $e) {
            $this->handleError($e);
            return false;
        }
    }

    // handle ERRORs in DEV mode
    public function handleError(PDOException $e)
    {
        // error for DEV mod
        if (defined('DEBUG') && DEBUG === true) {
            die('SQL error : ' . $e->getMessage());
        }
        // error for PROD mod
        else {
            die('Une erreur est survenue.');
        }
    }
}
