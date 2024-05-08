<?php

require_once __DIR__ . '/../src/routers.php';
require_once __DIR__ . '/../src/users/auth.php';    
require_once __DIR__ . '/../src/utils/permission_guards.php';
require_once __DIR__ . '/../src/utils/types.php';

$allowed_origins = ['*'];


$routes = [
    ['GET', '/', function () {}, Permission::Any],
    ['GET', '/hello/{name}', function ($params) {}, Permission::Admin],
    ['GET','/hi', function ($params) {}, Permission::User],

    ['POST', '/users/register', function () {}, Permission::Any],
    ['POST','/users/login', function () {}, Permission::Any],
];

if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)) {
    header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
    header('Access-Control-Allow-Methods: GET, POST'); // Add allowed methods as needed
    header('Access-Control-Allow-Headers: Content-Type, Authorization'); // Add allowed headers as needed
}

buildRouter($routes);