<?php

require_once __DIR__ . '/../src/routers.php';
require_once __DIR__ . '/../src/users/auth.php';    
require_once __DIR__ . '/../src/utils/permission_guards.php';
require_once __DIR__ . '/../src/utils/types.php';

$routes = [
    ['GET', '/', function () {}, Permission::Any],
    ['GET', '/hello/{name}', function ($params) {}, Permission::Admin],
    ['GET','/hi', function ($params) {}, Permission::User],

    ['POST', '/users/register', function () {}, Permission::Any],
    ['POST','/users/login', function () {}, Permission::Any],
    ['POST', '/users/data', function() {}, Permission::User],
    ['GET', '/users/data', function() {}, Permission::User],

    ['POST', '/panen', function () {}, Permission::User],
    ['GET', '/panen', function() {}, Permission::User],

    ['GET', '/whoami', function() {}, Permission::User],

    ['GET', '/users/panen', function() {}, Permission::User],

    ['GET', '/wilayah', function() {}, Permission::Any],

    ['POST', '/wilayah', function() {}, Permission::Admin],

    ['POST', '/wilayah/tanah', function() {}, Permission::Admin],

    ['POST', '/wilayah/air', function() {}, Permission::Admin],

    ['POST', '/wilayah/suhu', function() {}, Permission::Admin],

    ['GET', '/wilayah/tanah', function() {}, Permission::Any],

    ['GET', '/wilayah/air', function() {}, Permission::Any],

    ['GET', '/wilayah/suhu', function() {}, Permission::Any],

    ['POST', '/users/lahan', function() {}, Permission::User],
    
    ['GET', '/users/lahan', function() {}, Permission::User],

    ['POST', '/ruang-tani/artikel', function() {}, Permission::Admin],

    ['GET', '/ruang-tani', function() {}, Permission::Any],

    ['GET', '/ruang-tani/artikel', function() {}, Permission::Any],
];

buildRouter($routes);