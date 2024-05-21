<?php

function submit_daerah($name, $connection) {
    $stmt = $connection->prepare("INSERT INTO wilayah (name) VALUES (?)");

    $stmt->bindParam(1, $name);

    return $connection->execute($stmt, null);
}

?>