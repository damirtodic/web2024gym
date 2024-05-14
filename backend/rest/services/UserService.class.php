<?php
require_once __DIR__ . "/../dao/BaseDao.class.php";
require_once __DIR__ . "/../dao/UserDao.class.php";


class UserService{
    private $user_dao;
        public function __construct(){
            $this->user_dao = new UserDao();
        }
        public function add_user($user){
            return $this->user_dao->add_user($user);
        }
        public function login_user($email, $password) {
            return $this->user_dao->login_user($email, $password);
        }
        public function fetch_employees(){
            return $this->user_dao->fetch_employees();
        }
        public function delete_user($userId){
            return $this->user_dao->delete_user($userId);
        }
 
}
?>