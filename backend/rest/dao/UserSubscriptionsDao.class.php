<?php
require_once __DIR__ . "/BaseDao.class.php";

class UserSubscriptionsDao extends BaseDao {
    public function __construct() {
        parent::__construct('UserSubscriptions'); 
        }
    public function add_subscription($userId, $subscriptionId, $purchaseDate, $expirationDate) {
        try {
            $query = "INSERT INTO UserSubscriptions (user_id, subscription_id, purchase_date, expiration_date)
                      VALUES (:userId, :subscriptionId, :purchaseDate, :expirationDate)";
            
            $stmt = $this->connection->prepare($query);

            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':subscriptionId', $subscriptionId);
            $stmt->bindParam(':purchaseDate', $purchaseDate);
            $stmt->bindParam(':expirationDate', $expirationDate);

            $stmt->execute();

            return true; 
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    public function getActiveSubscriptions($userId) {
        try {
            $currentDate = date('Y-m-d');
            $query = "SELECT * FROM UserSubscriptions WHERE user_id = :userId AND expiration_date > :currentDate";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':currentDate', $currentDate);
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
