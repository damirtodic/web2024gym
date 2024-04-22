<?php
require_once __DIR__ . "/BaseDao.class.php";

class SubscriptionDao extends BaseDao {
    public function __construct() {
        parent::__construct('Subscriptions'); 
    }

    public function fetch_subscriptions() {
        try {
            $query = "SELECT * FROM Subscriptions";
            $stmt = $this->connection->prepare($query);
            $stmt->execute();
            $subscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $subscriptions;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
}

?>