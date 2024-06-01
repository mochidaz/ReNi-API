<?php

function delete_daerah($connection, $id) {
    $stmt = $connection->prepare("DELETE FROM wilayah WHERE id = ?");

    $stmt->bindParam(1, $id);

    $connection->execute($stmt, null);

    return $stmt->rowCount();
}

?>