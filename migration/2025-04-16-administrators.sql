use kuva;

CREATE TABLE IF NOT EXISTS `administrators` (
  `user_id` int (10) unsigned NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
