<?php

function submit_post($title, $content, $category, $image, $connection) {
    $stmt = $connection->prepare("INSERT INTO artikel (title, content, category, image) VALUES (:title, :content, :category, :image)");

    $stmt->bindParam(':title', $title);

    $stmt->bindParam(':content', $content);

    $stmt->bindParam(':category', $category);

    $stmt->bindParam(':image', $image);

    return $connection->execute($stmt, null);
}

function get_post($connection, $id = null) {
    if ($id != null) {
        $stmt = $connection->prepare('SELECT * FROM artikel WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    } else {
        $stmt = $connection->prepare('SELECT * FROM artikel');
        $connection->execute($stmt, null);
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
}

function get_post_by_category($category, $connection) {
    $stmt = $connection->prepare('SELECT * FROM artikel WHERE category = :category');

    $stmt->bindParam(':category', $category);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>