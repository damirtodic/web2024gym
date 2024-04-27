<?php
require_once __DIR__ . "/rest/services/UserService.class.php";
$payload = $_REQUEST;

// Error handling for required fields
if (empty($payload['name'])) {
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error' => 'First name is missing']));
}
if (empty($payload['dob'])) {
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error' => 'Date of birth is missing']));
}
if (empty($payload['password'])) {
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error' => 'Password is missing']));
}
if (empty($payload['email'])) {
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error' => 'Email is missing']));
}

// Validate email format
if (!filter_var($payload['email'], FILTER_VALIDATE_EMAIL)) {
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error' => 'Invalid email format']));
}

$user_service = new UserService();
$new_user = $user_service->add_user($payload);

echo json_encode(['message'=>'successfully registered user', 'data'=>$new_user]);

?>