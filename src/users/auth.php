<?php

include_once __DIR__ . '/../db/conn.php';

function createApiKey($length = 32)
{
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charsLength = strlen($chars);
    $key = '';
    for ($i = 0; $i < $length; $i++) {
        $key .= $chars[rand(0, $charsLength - 1)];
    }
    return $key;
}

function verifyApiKey($apiKey, $connection) {
    $row = $connection->query("SELECT * FROM users WHERE api_key = '$apiKey'");

    if ($row->num_rows > 0) {
        return true;
    }

    return false;
}

function login($no_ktp, $password, $connection) {
    $stmt = $connection->prepare("SELECT * FROM users WHERE no_ktp = :no_ktp");
    $stmt->bindParam(':no_ktp', $no_ktp);
    
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        return $user['token'];
    }

    return null;
}

function get_user_by_apikey($api_key, $connection) {
    $stmt = $connection->prepare("SELECT * FROM users WHERE token = :api_key");
    $stmt->bindParam(':api_key', $api_key);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    return $user ? $user : null;
}

function get_user_data_by_apikey($api_key, $connection) {
    // join with user_data
    
    $stmt = $connection->prepare("SELECT *, * FROM users WHERE token = :api_key");
}
    