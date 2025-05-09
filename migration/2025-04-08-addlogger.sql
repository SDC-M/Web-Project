use kuva;

CREATE TABLE IF NOT EXISTS `logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` text NOT NULL,
  `creation_date` datetime NOT NULL,
  `executed_by` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `logs_users_FK` (`executed_by`),
  CONSTRAINT `logs_users_FK` FOREIGN KEY (`executed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
