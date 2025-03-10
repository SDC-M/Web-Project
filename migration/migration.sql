CREATE DATABASE IF NOT EXISTS kuva;

USE kuva;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` text NOT NULL,
  `email` varchar(100) NOT NULL,
  `secret_answer` text NOT NULL,
  PRIMARY KEY (`username`),
  CONSTRAINT ID_unique UNIQUE(id) 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `migration` (
  `version` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `images` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `file_path` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `is_public` boolean NOT NULL,
  `image_date` datetime NOT NULL,
  `user_id` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `annotations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `image_id` int(10) unsigned NOT NULL,
  `user_id` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `position_x` float NOT NULL,
  `position_y` float NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`image_id`) REFERENCES `images`(`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO migration
VALUES ("1");

