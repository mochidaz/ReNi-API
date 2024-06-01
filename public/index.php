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

    ['POST', '/pangan', function() {}, Permission::Admin],

    ['GET', '/pangan', function() {}, Permission::Any],

    ['POST', '/info_tanah', function() {}, Permission::Admin],

    ['GET', '/info_tanah', function() {}, Permission::Any],

    ['POST', '/info_air', function() {}, Permission::Admin],

    ['GET', '/info_air', function() {}, Permission::Any],

    ['POST', '/info_suhu', function() {}, Permission::Admin],

    ['GET', '/info_suhu', function() {}, Permission::Any],
];

$deleteRoutes = [
    ['DELETE', '/users/panen/', function() {}, Permission::User],

    ['DELETE', '/users/lahan/', function() {}, Permission::User],

    ['DELETE', '/ruang-tani/artikel/', function() {}, Permission::Admin],

    ['DELETE', '/pangan/', function() {}, Permission::Admin],

    ['DELETE', '/info_tanah/', function() {}, Permission::Admin],

    ['DELETE', '/info_air/', function() {}, Permission::Admin],

    ['DELETE', '/info_suhu/', function() {}, Permission::Admin],
];

$updateRoutes = [
    ['PUT', '/users/panen/', function() {}, Permission::User],

    ['PUT', '/users/lahan/', function() {}, Permission::User],

    ['PUT', '/ruang-tani/artikel/', function() {}, Permission::Admin],

    ['PUT', '/pangan/', function() {}, Permission::Admin],

    ['PUT', '/info_tanah/', function() {}, Permission::Admin],

    ['PUT', '/info_air/', function() {}, Permission::Admin],

    ['PUT', '/info_suhu/', function() {}, Permission::Admin],

    ['PUT', '/user/data/profile-photo', function() {}, Permission::User],

    ['PUT', '/ruang-tani/image', function() {}, Permission::Admin],
];

buildRouter(array_merge($routes, $deleteRoutes, $updateRoutes));