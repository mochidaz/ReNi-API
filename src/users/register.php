<?php

include_once __DIR__ . '/../db/conn.php';
include_once __DIR__ . '/../utils/functions.php';
include_once __DIR__ . "/auth.php";
include_once __DIR__ . '/../utils/date.php';

function register($no_ktp, $nama, $role_id, $password, $connection) {
    $now = generate_now();

    $apiKey = createApiKey();

    $stmt = $connection->prepare("INSERT INTO users (no_ktp, name, role_id, password, created_at , token) VALUES (?, ?, ?, ?, ?, ?)");

    $stmt->bindParam(1, $no_ktp);

    $stmt->bindParam(2, $nama);

    $stmt->bindParam(3, $role_id);

    $stmt->bindParam(4, $password);

    $stmt->bindParam(5, $now);

    $stmt->bindParam(6, $apiKey);

    return $connection->execute($stmt, null);    
}

?>