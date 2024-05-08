<?php

require_once 'types.php';
include_once __DIR__ . '/../db/conn.php';

function generate_now() {
    return date('Y-m-d H:i:s');
}

function router($httpMethods, $route, $callback, $permission_guard, $exit = true) {
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

    $api_key = $_SERVER['HTTP_API_KEY'];

    $user = get_user_by_apikey($api_key, $connection);

    if ($permission_guard !== Permission::Any) {
        if ($user === null) {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
            return;
        } else {
            if ($permission_guard === Permission::Admin) {
                if ($user['role_id'] != 1) {
                    http_response_code(403);
                    echo json_encode(['message' => 'Forbidden']);
                    return;
                }
            } else if ($permission_guard === Permission::User) {
                if ($user['role_id'] != 2 && $user['role_id'] != 1) {
                    http_response_code(403);
                    echo json_encode(['message' => 'Forbidden']);
                    return;
                }
            }
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
