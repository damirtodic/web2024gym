<?php
require_once __DIR__ . '/../services/SubscriptionService.class.php';

Flight::set('subscription_service', new SubscriptionService());

    /**
     * @OA\Get(
     *      path="/subscriptions",
     *      tags={"SubscriptionList"},
     *      summary="Get List of Subscriptions",
     *      @OA\Response(
     *           response=200,
     *           description="Get all patients"
     *      ),
     * )
     */
Flight::route('GET /subscriptions', function(){
    $subscription_service = Flight::get('subscription_service');
    $subscriptions = $subscription_service->fetch_subscriptions();

    Flight::json($subscriptions);
});



?>
