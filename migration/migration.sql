CREATE DATABASE IF NOT EXISTS kuva;

USE kuva;

CREATE TABLE `users` (
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

CREATE TABLE `migration` (
  `version` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `images` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `file_path` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `is_public` boolean NOT NULL,
  `image_date` datetime NOT NULL,
  `user_id` int (10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `annotations` (
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

CREATE TABLE `followers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_follow` int(10) unsigned NOT NULL,
  `id_follower` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`, `id_follow`, `id_follower`),
  FOREIGN KEY (`id_follow`) REFERENCES `users`(`id`),
  FOREIGN KEY (`id_follower`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `likes` (
  `image_id` int(10) unsigned NOT NULL,
  `user_id` int (10) unsigned NOT NULL,
  PRIMARY KEY (`image_id`, `user_id`),
  FOREIGN KEY (`image_id`) REFERENCES `images`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `action_type` varchar(255) NOT NULL,
  `description` text,
  `created_date ` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO migration
VALUES ("1");

