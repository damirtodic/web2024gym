<?php
require_once __DIR__ . "/rest/services/UserService.class.php";

$user_service = new UserService();
$employees = $user_service->fetch_employees();

$team = [];

foreach ($employees as $employee) {
    $social_links = [
        'facebook' => $employee['facebook'],
        'twitter' => $employee['twitter'],
        'youtube' => $employee['youtube'],
        'instagram' => $employee['instagram'],
        'email' => $employee['email']
    ];

    $employee_data = [
        'name' => $employee['name'],
        'position' => $employee['position'],
        'image' => $employee['image'],
        'social' => $social_links
    ];

    $team[] = $employee_data;
}

$team_json = json_encode(['team' => $team], JSON_PRETTY_PRINT);
echo $team_json;
?>
