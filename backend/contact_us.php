<?php
require_once __DIR__ . "/rest/services/ContactUsService.class.php";

$payload = $_REQUEST;

if (empty($payload['name'])) {
    http_response_code(500); 
    die(json_encode(['error' => 'Name is missing']));
}
if (empty($payload['email'])) {
    http_response_code(500); 
    die(json_encode(['error' => 'Email is missing']));
}
if (empty($payload['message'])) {
    http_response_code(500);
    die(json_encode(['error' => 'Message is missing']));
}

if (!filter_var($payload['email'], FILTER_VALIDATE_EMAIL)) {
    http_response_code(500);
    die(json_encode(['error' => 'Invalid email format']));
}

$contactUsService = new ContactUsService();
$result = $contactUsService->addContactMessage($payload['name'], $payload['email'], $payload['message']);

if ($result) {
    echo json_encode(['message' => 'Message sent successfully']);
} else {
    http_response_code(500); 
    echo json_encode(['error' => 'Failed to send message']);
}
?>
