<?php

require __DIR__ . '/../../../vendor/autoload.php';

define('BASE_URL', 'http://localhost/web-2024/web2024gym/backend/');

error_reporting(0);

$openapi = \OpenApi\Generator::scan(['../../../rest/routes', './']);
header('Content-Type: application/x-yaml');
echo $openapi->toYaml();
?>
