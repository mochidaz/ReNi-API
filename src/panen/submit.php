<?php
// CREATE TABLE IF NOT EXISTS `data_panen` (
//     `id` int(11) NOT NULL AUTO_INCREMENT,
//     `pangan_id` int(11) NOT NULL,
//     `user_id` varchar(255) NOT NULL,
//     `jumlah` int(11) NOT NULL,
//     `tanggal_penanaman` date NOT NULL,
//     `tanggal_panen` date NOT NULL,
//     `hasil_panen` int(11),
//     `luas_penanaman` int(11) NOT NULL,
//     `created_at` datetime NOT NULL,
//     `updated_at` datetime NOT NULL,
//     PRIMARY KEY (`id`),
//     FOREIGN KEY (`pangan_id`) REFERENCES `pangan`(`id`),
//     FOREIGN KEY (`user_id`) REFERENCES `users`(`no_ktp`)
// );

function submit_panen($db, $panen) {
    $stmt = $db->prepare('INSERT INTO data_panen (pangan_id, user_id, tanggal_penanaman, tanggal_panen, hasil_panen, created_at, updated_at, lahan_id) VALUES (:pangan_id, :user_id, :tanggal_penanaman, :tanggal_panen, :hasil_panen, :created_at, :updated_at, :lahan_id)');

    $stmt->bindParam(':pangan_id', $panen['pangan_id']);

    $stmt->bindParam(':user_id', $panen['user_id']);

    $stmt->bindParam(':tanggal_penanaman', $panen['tanggal_penanaman']);

    $stmt->bindParam(':tanggal_panen', $panen['tanggal_panen']);

    $stmt->bindParam(':hasil_panen', $panen['hasil_panen']);

    $stmt->bindParam(':created_at', date('Y-m-d H:i:s'));

    $stmt->bindParam(':updated_at', date('Y-m-d H:i:s'));

    $stmt->bindParam(':lahan_id', $panen['lahan_id']);

    return $stmt->execute();
}

function get_panen($db) {
    if (isset($_GET['id'])) {
        $stmt = $db->prepare('SELECT id, pangan_id, user_id, tanggal_penanaman, tanggal_panen, hasil_panen, created_at, updated_at, lahan_id, users.name as user_name, pangan.name as pangan_name, lahan_petani.name as lahan_name FROM data_panen INNER JOIN users ON users.no_ktp = data_panen.user_id INNER JOIN pangan ON pangan.id = pangan_id INNER JOIN lahan_petani ON lahan_petani.id = lahan_id WHERE data_panen.id = :id');

        $stmt->bindParam(':id', $_GET['id']);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $stmt = $db->prepare('SELECT id, pangan_id, user_id, tanggal_penanaman, tanggal_panen, hasil_panen, created_at, updated_at, lahan_id, users.name as user_name, pangan.name as pangan_name, lahan_petani.name as lahan_name FROM data_panen INNER JOIN users ON users.no_ktp = data_panen.user_id INNER JOIN pangan ON pangan.id = pangan_id INNER JOIN lahan_petani ON lahan_petani.id = lahan_id');

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

function get_panen_by_user($db, $user_id) {
    $stmt = $db->prepare('SELECT id, pangan_id, user_id, tanggal_penanaman, tanggal_panen, hasil_panen, created_at, updated_at, lahan_id, users.name as user_name, pangan.name as pangan_name, lahan_petani.name as lahan_name FROM data_panen INNER JOIN users ON users.no_ktp = data_panen.user_id INNER JOIN pangan ON pangan.id = pangan_id INNER JOIN lahan_petani ON lahan_petani.user_id = :user_id');

    $stmt->bindParam(':user_id', $user_id);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>