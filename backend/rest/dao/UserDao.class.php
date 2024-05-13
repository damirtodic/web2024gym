<?php
require_once __DIR__ . "/BaseDao.class.php";
require_once __DIR__ . "/../config.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class UserDao extends BaseDao {
    public function __construct() {
        parent::__construct('Users'); 
    }

    public function add_user($user) {
        try {
            $user['password'] = password_hash($user['password'], PASSWORD_BCRYPT);
            $query = "INSERT INTO Users (name, dateOfBirth, password, email) 
                      VALUES (:name, :dateOfBirth, :password, :email)";
            
            $stmt = $this->connection->prepare($query);

            $stmt->bindParam(':name', $user['name']);
            $stmt->bindParam(':dateOfBirth', $user['dob']);
            $stmt->bindParam(':password', $user['password']);
            $stmt->bindParam(':email', $user['email']);

            $stmt->execute();

            return true; // Insert successful
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false; // Insert failed
        }
    }
    public function login_user($email, $password) {
        try {
            $query = "SELECT * FROM Users WHERE email = :email";
            
            $stmt = $this->connection->prepare($query);
    
            $stmt->bindParam(':email', $email);
    
            $stmt->execute();
    
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($user) {
                if (password_verify($password, $user['password'])) {
                    unset($user['password']);
                    $jwt_payload = [
                        'user' => $user,
                        'iat' => time(),
                        'exp' => time() + (60*60*24)
                    ];
                    $jwt_token = JWT::encode(
                        $jwt_payload,
                        JWT_SECRET,
                        'HS256',
                    );
                    return array_merge($user, ['token' => $jwt_token]);
                } else {
                    // Password does not match
                    return false;
                }
            } else {
                // User not found
                return false;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false; // Login failed
        }
    }
    public function fetch_employees() {
        try {
            $query = "SELECT name, position, image, facebook, twitter, youtube, instagram, email FROM Users WHERE role_id = 1";
            $stmt = $this->connection->prepare($query);
            $stmt->execute();
            $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $employees;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
    public function delete_user($user_id) {
        try {
            $query = "DELETE FROM Users WHERE id = :id";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':id', $user_id);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}

?>