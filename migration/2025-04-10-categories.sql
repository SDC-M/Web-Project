CREATE TABLE `categories` (
  `id` int(10) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `name` text NOT NULL,
  `creation_date` datetime NOT NULL,
   CONSTRAINT UC_Name UNIQUE (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE image_categorie (
	image_id INT UNSIGNED NOT NULL,
	categorie_id INT UNSIGNED NOT NULL,
	CONSTRAINT image_categories_images_FK FOREIGN KEY (image_id) REFERENCES kuva.images(id),
	CONSTRAINT image_categories_categories_FK FOREIGN KEY (categorie_id) REFERENCES kuva.categories(id)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4;
