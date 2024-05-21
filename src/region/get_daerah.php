<?php

function get_all_daerah($connection) {
    $stmt = $connection->prepare("SELECT * FROM wilayah");

    return $connection->execute($stmt, null);
}

function get_daerah_by_id($id, $connection) {
    $stmt = $connection->prepare("SELECT * FROM wilayah WHERE id = ?");

    $stmt->bindParam(1, $id);

    return $connection->execute($stmt, null);
}

?>