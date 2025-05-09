CREATE DATABASE IF NOT EXISTS kuva;

USE kuva;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` text NOT NULL,
  `recovery_key` text NOT NULL,
  `biography` varchar(250),
  `profile_image_path` varchar(255),
  PRIMARY KEY (`id`),
  CONSTRAINT user_unique UNIQUE(username),
  CONSTRAINT email_unique UNIQUE(email) 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `images` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `file_path` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `is_public` boolean NOT NULL,
  `image_date` datetime NOT NULL,
  `user_id` int (10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `annotations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `image_id` int(10) unsigned NOT NULL,
  `user_id` int (10) unsigned NOT NULL,
  `description` text NOT NULL,
  `position_x1` int NOT NULL,
  `position_y1` int NOT NULL,
  `position_x2` int NOT NULL,
  `position_y2` int NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`image_id`) REFERENCES `images`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `followers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_follow` int(10) unsigned NOT NULL,
  `id_follower` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`, `id_follow`, `id_follower`),
  FOREIGN KEY (`id_follow`) REFERENCES `users`(`id`),
  FOREIGN KEY (`id_follower`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `likes` (
  `image_id` int(10) unsigned NOT NULL,
  `user_id` int (10) unsigned NOT NULL,
  PRIMARY KEY (`image_id`, `user_id`),
  FOREIGN KEY (`image_id`) REFERENCES `images`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
