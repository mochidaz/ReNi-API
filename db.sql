CREATE TABLE IF NOT EXISTS `role` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `created_at` datetime NOT NULL,
    `updated_at` datetime NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `users` (
    `no_ktp` varchar(255) NOT NULL,
    `name` varchar(255) NOT NULL,
    `password` varchar(255) NOT NULL,
    `role_id` int(11) NOT NULL,
    `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `token` varchar(255) NOT NULL,
    PRIMARY KEY (`no_ktp`),
    FOREIGN KEY (`role_id`) REFERENCES `role`(`id`)
);

CREATE TABLE IF NOT EXISTS `user_data` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` varchar(255) NOT NULL,
    `address` varchar(255) NOT NULL,
    `phone` varchar(255) NOT NULL,
    `created_at` datetime NOT NULL,
    `updated_at` datetime NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`no_ktp`)
);

INSERT INTO `role` (`name`, `created_at`, `updated_at`)
VALUES ('admin', NOW(), NOW());

INSERT INTO `role` (`name`, `created_at`, `updated_at`)
VALUES ('user', NOW(), NOW());

INSERT INTO `users` (`no_ktp`, `name`, `password`, `role_id`, `token`)

VALUES ('1234567890', 'admin', 'admin', 1, 'admin');

INSERT INTO `users` (`no_ktp`, `name`, `password`, `role_id`, `token`)

VALUES ('123', 'user', 'user', 2, 'user');

CREATE TABLE IF NOT EXISTS `pangan` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `created_at` datetime NOT NULL,
    `updated_at` datetime NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `data_panen` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `pangan_id` int(11) NOT NULL,
    `user_id` varchar(255) NOT NULL,
    `jumlah` int(11) NOT NULL,
    `tanggal_penanaman` date NOT NULL,
    `tanggal_panen` date NOT NULL,
    `hasil_panen` int(11),
    `luas_penanaman` int(11) NOT NULL,
    `created_at` datetime NOT NULL,
    `updated_at` datetime NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`pangan_id`) REFERENCES `pangan`(`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`no_ktp`)
);

INSERT INTO `pangan` (`name`, `created_at`, `updated_at`)

VALUES ('Padi', NOW(), NOW());

INSERT INTO `pangan` (`name`, `created_at`, `updated_at`)

VALUES ('Jagung', NOW(), NOW());

INSERT INTO `pangan` (`name`, `created_at`, `updated_at`)

VALUES ('Kedelai', NOW(), NOW());

INSERT INTO `data_panen` (`pangan_id`, `user_id`, `jumlah`, `tanggal_penanaman`, `tanggal_panen`, `hasil_panen`, `luas_penanaman`, `created_at`, `updated_at`)

VALUES (1, '123', 100, '2021-01-01', '2021-03-01', 1000, 100, NOW(), NOW());

INSERT INTO `data_panen` (`pangan_id`, `user_id`, `jumlah`, `tanggal_penanaman`, `tanggal_panen`, `hasil_panen`, `luas_penanaman`, `created_at`, `updated_at`)

VALUES (2, '123', 100, '2021-01-01', '2021-03-01', 1000, 100, NOW(), NOW());

INSERT INTO `data_panen` (`pangan_id`, `user_id`, `jumlah`, `tanggal_penanaman`, `tanggal_panen`, `hasil_panen`, `luas_penanaman`, `created_at`, `updated_at`)

VALUES (3, '123', 100, '2021-01-01', '2021-03-01', 1000, 100, NOW(), NOW());

