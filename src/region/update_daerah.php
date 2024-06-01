<?php

function update_daerah($connection, $daerah) {
    $stmt = $connection->prepare('UPDATE wilayah SET name = :name WHERE id = :id');

    $stmt->bindParam(':name', $daerah['name']);

    $stmt->bindParam(':id', $daerah['id']);

    return $stmt->execute();
}

?>