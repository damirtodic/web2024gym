<?php
require_once __DIR__ . '/../services/UserSubscriptionsService.class.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

Flight::set('user_subscriptions_service', new UserSubscriptionsService());

    /**
     * @OA\GET(
     *      path="/user-subscriptions",
     *      tags={"UserSubscriptions"},
     *      summary="get user subscriptions",
     *           security={
     *          {"ApiKey": {}}   
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="Get all patients"
     *      ),

     * )
     */
Flight::route('GET /user-subscriptions', function(){
    $userId = Flight::get('user')->id;
    if (!$userId) {
        Flight::json(['error' => 'Missing user ID in the payload.'], 400);
        return;
    }


    $user_subscriptions_service = Flight::get('user_subscriptions_service');
    $subscriptions = $user_subscriptions_service->fetchActiveSubscriptions($userId);

    if ($subscriptions) {
        Flight::json(['subscriptions' => $subscriptions]);
    } else {
        Flight::json(['message' => 'No active subscriptions found for the user.']);
    }
});
/**
 * @OA\Post(
 *      path="/add-subscription",
 *      tags={"subscriptions"},
 *      summary="Add Subscription",
 *          security={
 *          {"ApiKey": {}}   
 *      },
 *      description="Endpoint to add a subscription for a user.",
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\MediaType(
 *              mediaType="application/x-www-form-urlencoded",
 *              @OA\Schema(
 *                  required={"subscription_id"},
 *                  @OA\Property(
 *                      property="subscription_id",
 *                      type="integer",
 *                      description="Subscription ID"
 *                  )
 *              )
 *          )
 *      ),
 *      @OA\Response(
 *           response=200,
 *           description="Subscription added successfully"
 *      ),
 *      @OA\Response(
 *           response=400,
 *           description="Bad request"
 *      ),
 *      @OA\Response(
 *           response=500,
 *           description="Failed to calculate expiration date"
 *      ),
 * )
 */
Flight::route('POST /add-subscription', function(){
    $payload = Flight::request()->data;
    $userId = Flight::get('user')->id;
    $subscriptionId = $payload['subscription_id'] ?? null;

    if (!$userId || !$subscriptionId) {
        Flight::json(['error' => 'Missing user ID or subscription ID in the payload.'], 400);
        return;
    }

    $purchaseDate = date('Y-m-d');
    $expirationDate = calculateExpirationDate($subscriptionId, $purchaseDate);

    if (!$expirationDate) {
        Flight::json(['error' => 'Failed to calculate expiration date.'], 500);
        return;
    }

    $user_subscriptions_service = Flight::get('user_subscriptions_service');
    $result = $user_subscriptions_service->add_subscription($userId, $subscriptionId, $purchaseDate, $expirationDate);

    if ($result) {
        Flight::json(['message' => 'Subscription added successfully.']);
    } else {
        Flight::json(['error' => 'Failed to add subscription.']);
    }
});

function calculateExpirationDate($subscriptionId, $purchaseDate) {
    switch ($subscriptionId) {
        case 1:
            return date('Y-m-d', strtotime('+1 month', strtotime($purchaseDate)));
        case 2:
            return date('Y-m-d', strtotime('+6 months', strtotime($purchaseDate)));
        case 3:
            return date('Y-m-d', strtotime('+1 year', strtotime($purchaseDate)));
        default:
            return null;
    }
}

?>
