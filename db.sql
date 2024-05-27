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

ALTER TABLE `data_panen` DROP COLUMN `jumlah`;

CREATE TABLE IF NOT EXISTS `wilayah` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `created_at` datetime NOT NULL,
    `updated_at` datetime NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `informasi_tanah` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `wilayah_id` int(11) NOT NULL,
    `content` text NOT NULL,
    `created_at` datetime NOT NULL,
    `updated_at` datetime NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`wilayah_id`) REFERENCES `wilayah`(`id`)
);

CREATE TABLE IF NOT EXISTS `informasi_suhu`(
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `wilayah_id` int(11) NOT NULL,
    `content` text NOT NULL,
    `created_at` datetime NOT NULL,
    `updated_at` datetime NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`wilayah_id`) REFERENCES `wilayah`(`id`)
);

CREATE TABLE IF NOT EXISTS `informasi_air`(
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `wilayah_id` int(11) NOT NULL,
    `content` text NOT NULL,
    `created_at` datetime NOT NULL,
    `updated_at` datetime NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`wilayah_id`) REFERENCES `wilayah`(`id`)
);

ALTER TABLE `user_data` ADD `profile_photo` varchar(255) NOT NULL;

CREATE TABLE IF NOT EXISTS `lahan_petani` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` varchar(255) NOT NULL,
    `luas_lahan` int(11) NOT NULL,
    `lokasi` varchar(255) NOT NULL,
    `created_at` datetime NOT NULL,
    `updated_at` datetime NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`no_ktp`)
);

ALTER TABLE `data_panen` ADD `lahan_id` int(11) NOT NULL;

SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `data_panen`
ADD CONSTRAINT `fk_data_panen_lahan_id`
FOREIGN KEY (`lahan_id`)
REFERENCES `lahan_petani`(`id`);

SET FOREIGN_KEY_CHECKS = 1;
s
ALTER TABLE `lahan_petani` ADD `name` varchar(255) NOT NULL;

ALTER TABLE `lahan_petani` ADD `wilayah_id` int(11) NOT NULL;

SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `lahan_petani`
ADD CONSTRAINT `fk_lahan_petani_wilayah_id`
FOREIGN KEY (`wilayah_id`)
REFERENCES `wilayah`(`id`);

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE IF NOT EXISTS `artikel` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL,
    `content` text NOT NULL,
    `category` varchar(255) NOT NULL,
    `created_at` datetime NOT NULL,
    `updated_at` datetime NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `artikel_image` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `artikel_id` int(11) NOT NULL,
    `image` varchar(255) NOT NULL,
    `created_at` datetime NOT NULL,
    `updated_at` datetime NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`artikel_id`) REFERENCES `artikel`(`id`)
);


-- Add DEFAULT CURRENT_TIMESTAMP to created_at and updated_at

ALTER TABLE `role` MODIFY `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `role` MODIFY `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `user_data` MODIFY `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `user_data` MODIFY `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `pangan` MODIFY `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `pangan` MODIFY `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `data_panen` MODIFY `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `data_panen` MODIFY `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `wilayah` MODIFY `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `wilayah` MODIFY `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `informasi_tanah` MODIFY `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `informasi_tanah` MODIFY `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `informasi_suhu` MODIFY `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `informasi_suhu` MODIFY `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `informasi_air` MODIFY `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `informasi_air` MODIFY `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `lahan_petani` MODIFY `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `lahan_petani` MODIFY `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `artikel` MODIFY `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `artikel` MODIFY `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `artikel_image` MODIFY `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `artikel_image` MODIFY `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
