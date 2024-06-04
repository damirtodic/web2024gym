<?php
require_once __DIR__ . "/../dao/BaseDao.class.php";
require_once __DIR__ . "/../dao/UserSubscriptionsDao.class.php";



class UserSubscriptionsService {
    private $userSubscriptions_dao;
    public function __construct(){
        $this->userSubscriptions_dao = new UserSubscriptionsDao();
    }
    public function add_subscription($userId, $subscriptionId, $purchaseDate, $expirationDate) {
        return $this->userSubscriptions_dao->add_subscription($userId, $subscriptionId, $purchaseDate, $expirationDate);
    }
    public function fetchActiveSubscriptions($userId) {
        return $this->userSubscriptions_dao->getActiveSubscriptions($userId);
    }
}

?>