<?php

namespace Kuva\Backend;

use DateTime;
use DateTimeInterface;
use JsonSerializable;
use PDO;

class Categories implements JsonSerializable
{
    private function __construct(
        private int $id,
        private string $name,
        private DateTime $creation_date
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCreationDate(): DateTime
    {
        return $this->creation_date;
    }

    public function getName(): string
    {
        return $this->name;
    }

    private static function fromRow(array $values): static
    {
        return new self($values["id"], $values["name"], new DateTime($values["creation_date"]));
    }

    public static function getByName(string $name): ?static
    {
        $db = (new Database())->db;
        $q = $db->prepare("SELECT * FROM categories WHERE name = :name");
        $q->bindValue("name", $name);
        $q->execute();
        $values = $q->fetch(PDO::FETCH_ASSOC);
        if ($values === false) {
            return null;
        }
        return self::fromRow($values);
    }


    public static function getById(int $id): ?static
    {
        $db = (new Database())->db;
        $q = $db->prepare("SELECT * FROM categories WHERE id = :id");
        $q->bindValue("id", $id);
        $q->execute();
        $values = $q->fetch(PDO::FETCH_ASSOC);
        if ($values === false) {
            return null;
        }
        return self::fromRow($values);
    }

    public function getPublicImageInCategories(): array
    {
        $db = (new Database())->db;
        $q = $db->prepare("SELECT image_id FROM image_categorie WHERE categorie_id = :id");
        $q->bindValue("id", $this->id);
        $q->execute();
        $values = $q->fetchAll(PDO::FETCH_ASSOC);
        if ($values === false) {
            return [];
        }

        $ids = array_map(fn ($e) => $e["image_id"], $values);
        return array_filter(array_map(fn ($id) => Image::getById($id), $ids), fn ($d) => $d !== null);
    }

    public static function create(string $name): ?static
    {
        $db = (new Database())->db;
        $q = $db->prepare("INSERT INTO categories(name, creation_date) VALUES (:name, :creation_date)");
        $q->bindValue("name", $name);
        $q->bindValue("creation_date", date("Y-m-d H:i:s"));
        $q->execute();

        return self::getByName($name);
    }

    public static function createOrGetIfAlreadyExists(string $name): static
    {
        $category = self::getByName($name);
        if ($category == null) {
            return self::create($name);
        }
        return $category;
    }

    public function linkImage(Image $image): bool
    {
        $db = (new Database())->db;
        $q = $db->prepare("INSERT INTO image_categorie(image_id, categorie_id) VALUES (:image_id, :categorie_id)");
        $q->bindValue("image_id", $image->getId());
        $q->bindValue("categorie_id", $this->getId());
        return $q->execute() !== false;
    }

    public function unlink(Image $image): bool
    {
        $db = (new Database())->db;
        $q = $db->prepare("DELETE FROM image_categorie WHERE image_id = :image_id AND categorie_id = :categorie_id");
        $q->bindValue("image_id", $image->getId());
        $q->bindValue("categorie_id", $this->getId());
        return $q->execute() !== false;
    }

    public static function ofImage(Image $image): array
    {
        $db = (new Database())->db;
        $q = $db->prepare("SELECT categorie_id FROM image_categorie WHERE image_id = :id");
        $q->bindValue("id", $image->getId());
        $q->execute();
        $values = $q->fetchAll(PDO::FETCH_ASSOC);
        if ($values === false) {
            return [];
        }

        $ids = array_map(fn ($e) => $e["categorie_id"], $values);
        return array_filter(array_map(fn ($id) => Categories::getById($id), $ids), fn ($d) => $d !== null);
    }

    public static function deleteAllOf(Image $image): bool
    {
        $db = (new Database())->db;
        $q = $db->prepare("DELETE FROM image_categorie WHERE image_id = :image_id");
        $q->bindValue("image_id", $image->getId());
        return $q->execute() !== false;
    }


    private static function getCategoriesFromDescription(string $description): array
    {
        $regex = "/#(\w+)/";
        $matches = [];
        $r = preg_match_all($regex, $description, $matches);
        return $matches[1];
    }

    public static function setFromDescription(Image $image, string $description): bool
    {
        $tags = self::getCategoriesFromDescription($description);
        self::deleteAllOf($image);
        foreach ($tags as $value) {
            $c = Categories::createOrGetIfAlreadyExists($value);
            $c->linkImage($image);
        }

        return false;
    }

    public function jsonSerialize(): mixed
    {
        return ["id" => $this->getId(), "name" => $this->getName(), "creation_date" => $this->getCreationDate()->format(DateTimeInterface::ATOM)];
    }


}
