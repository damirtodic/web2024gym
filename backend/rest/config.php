<?php

//set reporting
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL ^ (E_NOTICE | E_DEPRECATED));

//Database access credentials
define('DB_NAME','gym_project');
define('DB_PORT',3306);
define('DB_USER','root');
define('DB_PASSWORD','');
define('DB_HOST','127.0.0.1');
define('BASEURL', 'http://localhost/web-2024/web2024gym');

//JWT Secret
define('JWT_SECRET','todinjojemajstor');
?>