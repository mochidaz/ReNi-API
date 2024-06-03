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

function delete_post($connection, $id) {
    $stmt = $connection->prepare('DELETE FROM artikel WHERE id = :id');
    $stmt->bindParam(':id', $id);
    return $connection->execute($stmt, null);
}

function update_post($connection, array $post) {

    $stmt = $connection->prepare('UPDATE artikel SET title = :title, content = :content, category = :category, updated_at = NOW() WHERE id = :id');
    $stmt->bindParam(':title', $post['title']);
    $stmt->bindParam(':content', $post['content']);
    $stmt->bindParam(':category', $post['category']);
    $stmt->bindParam(':id', $post['id']);
    
    $res = $connection->execute($stmt, null);
    
    return $res;
}

function update_post_image($connection, $id, $image) {
    $stmt = $connection->prepare('UPDATE artikel SET image = :image WHERE id = :id');
    $stmt->bindParam(':image', $image);
    $stmt->bindParam(':id', $id);
    return $connection->execute($stmt, null);
}

?>