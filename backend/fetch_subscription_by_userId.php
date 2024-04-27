<?php
require_once __DIR__ . "/rest/services/UserSubscriptionsService.class.php";

$userId = $_POST['user_id'] ?? null;


if (!$userId) {
    http_response_code(400);
    echo json_encode(array("error" => "Missing user ID in the payload."));
} else {
    $userSubscriptionsService = new UserSubscriptionsService();
    $subscriptions = $userSubscriptionsService->fetchActiveSubscriptions($userId);

    if ($subscriptions) {
        echo json_encode(array("subscriptions" => $subscriptions));
    } else {
        echo json_encode(array("message" => "No active subscriptions found for the user."));
    }
}
?>
