<?php


function insert_lahan($name, $wilayah_id, $luas_lahan, $lokasi, $user_id, $connection) {
    $stmt = $connection->prepare('INSERT INTO lahan_petani (name, luas_lahan, wilayah_id, lokasi, user_id, created_at, updated_at) VALUES (:name, :luas_lahan, :lokasi, :user_id, :created_at, :updated_at)');

    $stmt->bindParam(':name', $name);

    $stmt->bindParam(':luas_lahan', $luas_lahan);

    $stmt->bindParam(':wilayah_id', $wilayah_id);

    $stmt->bindParam(':lokasi', $lokasi);

    $stmt->bindParam(':user_id', $user_id);

    $stmt->bindParam(':created_at', date('Y-m-d H:i:s'));

    $stmt->bindParam(':updated_at', date('Y-m-d H:i:s'));

    return $stmt->execute();
}

function get_lahan_by_user($user_id, $connection) {
    //$stmt = $connection->prepare('SELECT * FROM lahan_petani WHERE user_id = :user_id');

    $stmt = $connection->prepare('SELECT  lahan_petani.id, lahan_petani.name, lahan_petani.luas_lahan, lahan_petani.lokasi, wilayah.name as wilayah, wilayah_id FROM lahan_petani INNER JOIN wilayah ON lahan_petani.wilayah_id = wilayah.id WHERE user_id = :user_id');

    $stmt->bindParam(':user_id', $user_id);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_lahan($connection) {
    if (isset($_GET['id'])) {
        $stmt = $connection->prepare('SELECT lahan_petani.id, lahan_petani.name, lahan_petani.luas_lahan, lahan_petani.lokasi, wilayah.name as wilayah, wilayah_id FROM lahan_petani INNER JOIN wilayah ON lahan_petani.wilayah_id = wilayah.id WHERE lahan_petani.id = :id');

        $stmt->bindParam(':id', $_GET['id']);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $stmt = $connection->prepare('SELECT lahan_petani.id, lahan_petani.name, lahan_petani.luas_lahan, lahan_petani.lokasi, wilayah.name as wilayah, wilayah_id FROM lahan_petani INNER JOIN wilayah ON lahan_petani.wilayah_id = wilayah.id');
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

function get_all_lahan($connection) {
    $stmt = $connection->prepare('SELECT lahan_petani.id, lahan_petani.name, lahan_petani.luas_lahan, lahan_petani.lokasi, wilayah.name as wilayah, wilayah_id FROM lahan_petani INNER JOIN wilayah ON lahan_petani.wilayah_id = wilayah.id');

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>