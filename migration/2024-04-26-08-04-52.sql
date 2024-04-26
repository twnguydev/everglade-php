ALTER TABLE `genre` MODIFY COLUMN `id` integer AUTO_INCREMENT NOT NULL, MODIFY COLUMN `id_movie` INT NOT NULL, MODIFY COLUMN `id_genre` INT NOT NULL;
ALTER TABLE `movie` MODIFY COLUMN `id` integer AUTO_INCREMENT NOT NULL, MODIFY COLUMN `title` VARCHAR(255) NOT NULL, MODIFY COLUMN `id_genre` INT NOT NULL, MODIFY COLUMN `director` VARCHAR(255) NOT NULL, MODIFY COLUMN `release_date` DATETIME NOT NULL;
ALTER TABLE `movie_genre` MODIFY COLUMN `id` integer AUTO_INCREMENT NOT NULL, MODIFY COLUMN `name` VARCHAR(255) NOT NULL;
ALTER TABLE `user` MODIFY COLUMN `id` integer AUTO_INCREMENT NOT NULL, MODIFY COLUMN `gender` VARCHAR(30) NOT NULL, MODIFY COLUMN `token` VARCHAR(255), MODIFY COLUMN `email` VARCHAR(255) NOT NULL, MODIFY COLUMN `username` VARCHAR(255) NOT NULL, MODIFY COLUMN `password` VARCHAR(255) NOT NULL, MODIFY COLUMN `phone` VARCHAR(255) NOT NULL, MODIFY COLUMN `firstname` VARCHAR(255) NOT NULL, MODIFY COLUMN `lastname` VARCHAR(255) NOT NULL, MODIFY COLUMN `birthdate` DATE NOT NULL, MODIFY COLUMN `role` VARCHAR(255) NOT NULL, MODIFY COLUMN `status` VARCHAR(255) NOT NULL, MODIFY COLUMN `created_at` DATE NOT NULL, MODIFY COLUMN `updated_at` DATE NOT NULL, MODIFY COLUMN `deleted_at` DATE;
