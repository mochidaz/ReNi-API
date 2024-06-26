<?php

require_once 'types.php';
include_once __DIR__ . '/../db/conn.php';
include_once __DIR__ . '/permission_guards.php';

function router($httpMethods, $route, $callback, $permission_guard, $exit = true) {
    header("Access-Control-Allow-Origin: *");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, API_KEY');
    static $path = null;
    if ($path === null) {
        $path = parse_url($_SERVER['REQUEST_URI'])['path'];
        $scriptName = dirname(dirname($_SERVER['SCRIPT_NAME']));
        $scriptName = str_replace('\\', '/', $scriptName);
        $len = strlen($scriptName);
        if ($len > 0 && $scriptName !== '/') {
            $path = substr($path, $len);
        }
    }

    if (!in_array($_SERVER['REQUEST_METHOD'], (array) $httpMethods)) {
        return;
    }

    $matches = null;
    $regex = '~^' . str_replace(['/', '{', '}'], ['\/', '(?P<', '>[^\/]+)'], $route) . '$~';
    if (!preg_match($regex, $path, $matches)) {
        return;
    }
    
    global $connection;
    
    $api_key = null;
    $user = null;

    if (isset($_SERVER['HTTP_API_KEY'])) {
        $api_key = $_SERVER['HTTP_API_KEY'];

        $user = get_user_by_apikey($api_key, $connection);
    }

    if ($permission_guard !== Permission::Any) {
        if ($user === null) {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
            return;
        } 

        switch ($permission_guard) {
            case Permission::Admin:
                if (!is_admin($user)) {
                    http_response_code(403);
                    echo json_encode(['message'=> 'Forbidden']);
                    return;
                }
                break;

            case Permission::User:
                if (!is_admin_or_user($user)) {
                    http_response_code(403);
                    echo json_encode(['message'=> 'Forbidden']);
                    return;
                }
                break;
        }
    }

    if (empty($matches)) {
        $callback();
    } else {
        $params = array();
        foreach ($matches as $k => $v) {
            if (!is_numeric($k) && !empty($v)) {
                $params[$k] = $v;
            }
        }
        $callback($params);
    }

    if ($exit) {
        exit;
    }
}