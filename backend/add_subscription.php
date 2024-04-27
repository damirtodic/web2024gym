<?php
require_once __DIR__ . "/rest/services/UserSubscriptionsService.class.php";
$payload = $_REQUEST;
$userId = $payload['user_id'] ?? null;
$subscriptionId = $payload['subscription_id'] ?? null;

if (!$userId || !$subscriptionId) {
    http_response_code(400); // Bad Request
    echo json_encode(array("error" => "Missing user ID or subscription ID in the payload."));
} else {
    $userSubscriptionsService = new UserSubscriptionsService();
    $purchaseDate = date('Y-m-d');
    $expirationDate = calculateExpirationDate($subscriptionId, $purchaseDate);

    if (!$expirationDate ) {
        http_response_code(500); // Internal Server Error
        echo json_encode(array("error" => "Failed to calculate expiration date."));
    } else {
        $result = $userSubscriptionsService->add_subscription($userId, $subscriptionId, $purchaseDate, $expirationDate);

        if ($result) {
            echo json_encode(array("message" => "Subscription added successfully."));
        } else {
            echo json_encode(array("message" => "Failed to add subscription."));
        }
    }
}

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
