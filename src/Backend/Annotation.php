<?php

namespace Kuva\Backend;

use Exception;
use JsonSerializable;
use PDO;

class Annotation implements JsonSerializable
{
    private function __construct(
        public readonly ?int $id = null,
        public ?Image $image = null,
        public ?User $user = null,
        public ?string $description = null,
        private ?int $x1 = null,
        private ?int $y1 = null,
        private ?int $x2 = null,
        private ?int $y2 = null,
    ) {
    }

    public static function createWithUserAndImage(Image $image, User $user): self
    {
        return new self(null, $image, $user);
    }

    public function setFirstPoint(int $x, int $y): void
    {
        $this->x1 = $x;
        $this->y1 = $y;
    }


    public function setSecondPoint(int $x, int $y): void
    {
        $this->x2 = $x;
        $this->y2 = $y;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }



    public function addToDatabase(): bool
    {
        $db = new Database();

        $q = $db->db->prepare("INSERT INTO kuva.annotations (image_id,user_id,description,position_x1,position_y1,position_x2,position_y2)
	VALUES (:image_id,:user_id,:description,:x1,:y1,:x2,:y2);");
        $q->bindValue(":image_id", $this->image->getId());
        $q->bindValue(":user_id", $this->user->id);
        $q->bindValue(":description", $this->description);
        $q->bindValue(":x1", $this->x1);
        $q->bindValue(":y1", $this->y1);
        $q->bindValue(":x2", $this->x2);
        $q->bindValue(":y2", $this->y2);

        try {
            return $q->execute();
        } catch (Exception $e) {
            return false;
        }
    }


    public static function fromRow(array $row): self
    {
        $annotation = new self($row['id'], Image::getById($row['image_id']), User::getById($row['user_id']), $row['description']);
        $annotation->setFirstPoint($row['position_x1'], $row['position_y1']);
        $annotation->setSecondPoint($row['position_x2'], $row['position_y2']);

        return $annotation;
    }

    public static function getAllOfImage(Image $image): array
    {
        $db = new Database();
        $q = $db->db->prepare("SELECT * FROM annotations WHERE image_id = :image_id");
        $q->bindValue(":image_id", $image->getId());
        $values = $q->execute();
        $annotations = [];
        foreach ($q->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $annotations[] = Annotation::fromRow($row);
        }

        return $annotations;
    }

    public static function getById(int $id): ?static
    {
        $db = new Database();
        $q = $db->db->prepare("SELECT * FROM annotations WHERE id = :id");
        $q->bindValue(":id", $id);
        $values = $q->execute();
        $row = $q->fetch(PDO::FETCH_ASSOC);
        if ($row == null) {
            return null;
        }
        return static::fromRow($row);
    }

    public function delete(): bool
    {
        if ($this->id == null) {
            return false;
        }

        $db = new Database();
        $q = $db->db->prepare("DELETE FROM annotations WHERE id = :id");
        $q->bindValue(":id", $this->id);
        try {
            return $q->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    public function jsonSerialize(): mixed
    {
        $points = [["x" => $this->x1, "y" => $this->y1], ["x" => $this->x2, "y" => $this->y2]];
        return ["id" => $this->id, "image_id" => $this->image->getId(), "user" => $this->user, "description" => $this->description, "points" => $points];
    }
}
