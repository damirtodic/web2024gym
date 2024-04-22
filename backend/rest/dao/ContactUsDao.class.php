<?php
require_once __DIR__ . "/BaseDao.class.php";

class ContactUsDao extends BaseDao {
    public function __construct() {
        parent::__construct('ContactUs');
    }

    public function addContactMessage($name, $email, $message) {
        try {
            $query = "INSERT INTO ContactUs (name, email, message_content)
                      VALUES (:name, :email, :message)";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':message', $message);
            $stmt->execute();
            return true; // Insertion successful
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false; // Insertion failed
        }
    }
}
?>
