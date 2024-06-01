<?php

// same as ./soil_info.php

function submit_water_info($wilayah_id, $content, $connection) {
    $stmt = $connection->prepare("INSERT INTO informasi_air (wilayah_id, content) VALUES (:wilayah_id, :content)");

    $stmt->bindParam(':wilayah_id', $wilayah_id);

    $stmt->bindParam(':content', $content);

    return $connection->execute($stmt, null);
}

function get_water_info($connection, $id = null) {
    if ($id != null) {
        //$stmt = $connection->prepare('SELECT * FROM informasi_tanah WHERE id = :id');
        $stmt = $connection->prepare('SELECT informasi_air.id as air_id, informasi_air.content, wilayah.name as wilayah FROM informasi_air INNER JOIN wilayah ON informasi_air.wilayah_id = wilayah.id WHERE informasi_air.id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    } else {
        $stmt = $connection->prepare('SELECT informasi_air.id as air_id, informasi_air.content, wilayah.name as wilayah FROM informasi_air INNER JOIN wilayah ON informasi_air.wilayah_id = wilayah.id');
        $connection->execute($stmt, null);
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
}

function get_water_by_wilayah($wilayah_id, $connection) {
    $stmt = $connection->prepare('SELECT informasi_air.id as air_id, informasi_air.content, wilayah.name as wilayah FROM informasi_air INNER JOIN wilayah ON informasi_air.wilayah_id = wilayah.id WHERE wilayah_id = :wilayah_id');

    $stmt->bindParam(':wilayah_id', $wilayah_id);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function delete_water_info($connection, $id) {
    $stmt = $connection->prepare('DELETE FROM informasi_air WHERE id = :id');
    $stmt->bindParam(':id', $id);
    return $connection->execute($stmt, null);
}

function update_water_info($connection, $water_info) {
    $stmt = $connection->prepare('UPDATE informasi_air SET content = :content WHERE id = :id');
    $stmt->bindParam(':content', $water_info['content']);
    $stmt->bindParam(':id', $water_info['id']);
    return $connection->execute($stmt, null);
}

?>