<?php
require 'vendor/autoload.php';
require_once 'rest/routes/middleware_routes.php';
require_once 'rest/routes/user_routes.php';
require_once 'rest/routes/subscription_routes.php';
require_once 'rest/routes/user_subscriptions_routes.php';
require_once 'rest/routes/contact_us_routes.php';



Flight::start();
?>