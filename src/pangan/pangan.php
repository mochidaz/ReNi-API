<?php

function submit_pangan($db, $pangan) {
    $stmt = $db->prepare("INSERT INTO pangan (name) VALUES (:name)");

    $stmt->bindParam(':name', $pangan);

    return $stmt->execute();
}

function get_pangan($db, $id = null) {
    if ($id != null) {
        $stmt = $db->prepare('SELECT * FROM pangan WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    } else {
        $stmt = $db->prepare('SELECT * FROM pangan');
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }
}

?>