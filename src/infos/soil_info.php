<?php

function submit_soil_info($wilayah_id, $content, $connection) {
    $stmt = $connection->prepare("INSERT INTO informasi_tanah (wilayah_id, content) VALUES (:wilayah_id, :content)");

    $stmt->bindParam(':wilayah_id', $wilayah_id);

    $stmt->bindParam(':content', $content);

    return $connection->execute($stmt, null);
}

function get_soil_info($connection, $id = null) {
    if ($id != null) {
        //$stmt = $connection->prepare('SELECT * FROM informasi_tanah WHERE id = :id');
        $stmt = $connection->prepare('SELECT informasi_tanah.id as tanah_id, informasi_tanah.content, wilayah.name as wilayah FROM informasi_tanah INNER JOIN wilayah ON informasi_tanah.wilayah_id = wilayah.id WHERE informasi_tanah.id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    } else {
        $stmt = $connection->prepare('SELECT informasi_tanah.id as tanah_id, informasi_tanah.content, wilayah.name as wilayah FROM informasi_tanah INNER JOIN wilayah ON informasi_tanah.wilayah_id = wilayah.id');
        $connection->execute($stmt, null);
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
}

function get_soil_by_wilayah($wilayah_id, $connection) {
    $stmt = $connection->prepare('SELECT informasi_tanah.id as tanah_id, informasi_tanah.content, wilayah.name as wilayah FROM informasi_tanah INNER JOIN wilayah ON informasi_tanah.wilayah_id = wilayah.id WHERE wilayah_id = :wilayah_id');

    $stmt->bindParam(':wilayah_id', $wilayah_id);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>