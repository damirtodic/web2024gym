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
    public function paginated_users($search_param, $page) {
        try {
            $page_to_fetch = isset($page) ? $page : 1;
            $limit = 10;
            $offset = ($page_to_fetch - 1) * $limit;
    
            // Query to get the total count of users with valid subscriptions
            $count_query = "
                SELECT COUNT(*) as total
                FROM Users
                INNER JOIN (
                    SELECT user_id
                    FROM UserSubscriptions
                    WHERE expiration_date > NOW()
                    GROUP BY user_id
                ) AS valid_subscriptions
                ON Users.id = valid_subscriptions.user_id
                WHERE Users.role_id = 2";
    
            if (!empty($search_param)) {
                $count_query .= " AND Users.name LIKE :search_param";
            }
    
            $count_stmt = $this->connection->prepare($count_query);
    
            if (!empty($search_param)) {
                $search_term = "%" . $search_param . "%";
                $count_stmt->bindParam(':search_param', $search_term, PDO::PARAM_STR);
            }
    
            $count_stmt->execute();
            $total_results = $count_stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
            // Query to get paginated users with their latest valid subscriptions
            $query = "
                SELECT 
                    Users.id, Users.name, Users.dateOfBirth, Users.email, Users.role_id,
                    latest_subscription.id AS subscription_id, latest_subscription.expiration_date
                FROM Users
                INNER JOIN (
                    SELECT user_id, id, expiration_date
                    FROM UserSubscriptions
                    WHERE (user_id, expiration_date) IN (
                        SELECT user_id, MAX(expiration_date)
                        FROM UserSubscriptions
                        WHERE expiration_date > NOW()
                        GROUP BY user_id
                    )
                ) AS latest_subscription
                ON Users.id = latest_subscription.user_id
                WHERE Users.role_id = 2";
    
            if (!empty($search_param)) {
                $query .= " AND Users.name LIKE :search_param";
            }
    
            $query .= " LIMIT :limit OFFSET :offset";
    
            $stmt = $this->connection->prepare($query);
    
            if (!empty($search_param)) {
                $stmt->bindParam(':search_param', $search_term, PDO::PARAM_STR);
            }
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            return [
                'total' => $total_results,
                'users' => $users
            ];
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    
    
    
    
    
}

?>