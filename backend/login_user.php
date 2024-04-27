<?php
require_once __DIR__ . "/rest/services/UserService.class.php";
$payload = $_REQUEST;

// Error handling for required fields
if (empty($payload['email'])) {
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error' => 'Email is missing']));
}
if (empty($payload['password'])) {
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error' => 'Password is missing']));
}

$user_service = new UserService();
$login_result = $user_service->login_user($payload['email'], $payload['password']);

if ($login_result !== false) {
    // Login successful
    echo json_encode(['message'=>'successfully logged in', 'data'=>$login_result]);
} else {
    // Login failed
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(['error' => 'Invalid email or password']);
}
?>
