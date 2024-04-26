CREATE TABLE IF NOT EXISTS `history` (`id` integer AUTO_INCREMENT NOT NULL, `id_movie` INT NOT NULL, `id_user` INT NOT NULL, `date` DATETIME NOT NULL, PRIMARY KEY (`id`));
CREATE TABLE IF NOT EXISTS `movie` (`id` integer AUTO_INCREMENT NOT NULL, `title` VARCHAR(255) NOT NULL, `id_genre` INT NOT NULL, `director` VARCHAR(255) NOT NULL, `release_date` DATETIME NOT NULL, PRIMARY KEY (`id`));
CREATE TABLE IF NOT EXISTS `movie_genre` (`id` integer AUTO_INCREMENT NOT NULL, `name` VARCHAR(255) NOT NULL, PRIMARY KEY (`id`));
CREATE TABLE IF NOT EXISTS `user` (`id` integer AUTO_INCREMENT NOT NULL, `gender` VARCHAR(30) NOT NULL, `token` VARCHAR(255), `email` VARCHAR(255) NOT NULL, `username` VARCHAR(255) NOT NULL, `password` VARCHAR(255) NOT NULL, `phone` VARCHAR(255) NOT NULL, `firstname` VARCHAR(255) NOT NULL, `lastname` VARCHAR(255) NOT NULL, `birthdate` DATE NOT NULL, `role` VARCHAR(255) NOT NULL, `status` VARCHAR(255) NOT NULL, `created_at` DATE NOT NULL, `updated_at` DATE NOT NULL, `deleted_at` DATE, PRIMARY KEY (`id`));
