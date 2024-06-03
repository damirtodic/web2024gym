<?php
require_once __DIR__ . "/../dao/BaseDao.class.php";
require_once __DIR__ . "/../dao/SubscriptionDao.class.php";


class SubscriptionService{
    private $subscription_dao;
        public function __construct(){
            $this->subscription_dao = new SubscriptionDao();
        }
        public function fetch_subscriptions(){
            return $this->subscription_dao->fetch_subscriptions();
        }
}
?>