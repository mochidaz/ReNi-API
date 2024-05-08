<?php

require_once __DIR__ . '/../src/routers.php';
require_once __DIR__ . '/../src/users/auth.php';    
require_once __DIR__ . '/../src/utils/permission_guards.php';
require_once __DIR__ . '/../src/utils/types.php';

header("Access-Control-Allow-Origin: *"); // Replace with allowed origin
header('Access-Control-Allow-Credentials: true'); // Optional for cookies/sessions
header('Access-Control-Allow-Methods: *'); // Allowed request methods
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With'); // Allowed request headers

$routes = [
    ['GET', '/', function () {}, Permission::Any],
    ['GET', '/hello/{name}', function ($params) {}, Permission::Admin],
    ['GET','/hi', function ($params) {}, Permission::User],

    ['POST', '/users/register', function () {}, Permission::Any],
    ['POST','/users/login', function () {}, Permission::Any],
];

buildRouter($routes);