<?php

require_once __DIR__ . '/../utils/files.php';

function submit_user_data($user_id, $address, $phone, $profile_photo, $conn)
{
    $db = $conn;
    $query = $db->prepare('INSERT INTO user_data (user_id, address, phone, profile_photo, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())');
    $query->execute([$user_id, $address, $phone, $profile_photo]);
}

function get_user_data($user_id, $conn)
{
    $stmt = $conn->prepare('SELECT users.no_ktp as no_ktp, users.name as user_name, role.name as role_name, user_data.id as user_data_id, user_data.address as address, user_data.phone as phone, user_data.profile_photo as profile_photo FROM user_data INNER JOIN users ON user_data.user_id = users.no_ktp INNER JOIN role ON users.role_id = role.id WHERE user_id = :user_id');

    $stmt->bindParam(':user_id', $user_id);

    $stmt->execute();
    
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function get_bulk_user_data($conn)
{
    $db = $conn;
    $query = $db->prepare('SELECT users.no_ktp as no_ktp, users.name as user_name, role.name as role_name, user_data.id as user_data_id, user_data.address as address, user_data.phone as phone, user_data.profile_photo as profile_photo FROM user_data INNER JOIN users ON user_data.user_id = users.no_ktp INNER JOIN role ON users.role_id = role.id');

    $query->execute();

    return $query->fetchAll(PDO::FETCH_ASSOC);
}

function update_user_data($user_data, $conn)
{
    $db = $conn;
    $query = $db->prepare('UPDATE user_data SET address = :address, phone = :phone, updated_at = NOW() WHERE id = :id');

    $query->bindParam(':address', $user_data['address']);
    $query->bindParam(':phone', $user_data['phone']);

    $query->execute();
}

function update_user_profile_photo($user_id, $profile_photo, $conn)
{
    $db = $conn;
    $query = $db->prepare('UPDATE user_data SET profile_photo = :profile_photo WHERE user_id = :user_id');

    $query->bindParam(':profile_photo', $profile_photo);
    $query->bindParam(':user_id', $user_id);

    $query->execute();
}

?>