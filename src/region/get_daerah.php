<?php

function get_all_daerah($connection) {
    $stmt = $connection->prepare("SELECT * FROM wilayah");

    $connection->execute($stmt, null);

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

function get_daerah_by_id($id, $connection) {
    $stmt = $connection->prepare("SELECT * FROM wilayah WHERE id = ?");

    $stmt->bindParam(1, $id);

    $connection->execute($stmt, null);

    $daerah = $connection->fetch(PDO::FETCH_ASSOC);

    return $daerah;
}

?>