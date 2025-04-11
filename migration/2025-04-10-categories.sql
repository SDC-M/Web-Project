CREATE TABLE `categories` (
  `id` int(10) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `name` text NOT NULL,
  `creation_date` datetime NOT NULL,
   CONSTRAINT UC_Name UNIQUE (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `image_categorie` (
  `image_id` int(10) unsigned NOT NULL,
  `categorie_id` int(10) unsigned NOT NULL,
  KEY `image_categories_categories_FK` (`categorie_id`),
  KEY `image_categories_images_FK` (`image_id`),
  CONSTRAINT `image_categories_categories_FK` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `image_categories_images_FK` FOREIGN KEY (`image_id`) REFERENCES `images` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
