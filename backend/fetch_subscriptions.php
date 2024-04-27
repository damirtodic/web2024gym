<?php
require_once __DIR__ . "/rest/services/SubscriptionService.class.php";

$subscription_service = new SubscriptionService();
$subscriptions = $subscription_service->fetch_subscriptions();

$subscriptions_json = json_encode($subscriptions);
echo $subscriptions_json;
?>
