<?php

namespace Kuva\Backend;

use AssertionError;
use JsonSerializable;
use PDO;

class Image implements JsonSerializable
{
    public const string IMAGE_FOLDER = "../images/";

    private function __construct(
        public readonly ?string $name,
        public bool $is_public,
        public ?User $owner,
        public ?string $bytes
    ) {
    }

    public static function fromBytes(string $bytes): static
    {
        return new static(generateRandomString() . ".image", null, $bytes);
    }

    public static function fromFile(string $tmpfile): static {
        return self::fromBytes(file_get_contents($tmpfile));
    }

    public static function getById(int $id): ?static {
        $db = new Database();
        $q = $db->db->prepare("SELECT * FROM images WHERE id = :id");
        $q->bindValue("id", $id);
        $q->execute();
        $values = $q->fetch();

        return new static($values["file_path"], $values["is_public"] == 1, User::getById($values["user_id"]), "");
    }

    public function getPath(): string {
        return self::IMAGE_FOLDER . $this->owner->id . '/' . $this->name;
    }

    public function getBytes(): string {
        return file_get_contents($this->getPath());
    }

    public function linkTo(User $owner): void
    {
        $this->owner = $owner;
    }

    /**
     * Create image as file and an entry in db
     */
    public function commit(): void
    {
        if ($this->owner == null) {
            throw new AssertionError("Owner should not be null");
        }

        if ($this->name == null || $this->name == '') {
            throw new AssertionError("Image name should not be null");
        }

        $this->writeToFolder();
        $this->addToDatabase();
    }

    private function writeToFolder(): void
    {
        if (!file_exists(self::IMAGE_FOLDER)) {
            mkdir(self::IMAGE_FOLDER);
        }

        if (!file_exists(self::IMAGE_FOLDER . $this->owner->id)) {
            mkdir(self::IMAGE_FOLDER . $this->owner->id);
        }
        
        file_put_contents($this->getPath(), $this->bytes);
    }

    private function addToDatabase(): void
    {
        $db = new Database();
        $q = $db->db->prepare('INSERT INTO images(file_path, description, is_public, image_date, user_id)'
        . 'VALUES (:file_path, :description, :is_public, :image_date, :owner_id)');

        $q->bindValue(":file_path", $this->name);
        $q->bindValue(":description", "");
        $q->bindValue(":is_public", false, PDO::PARAM_BOOL);
        $q->bindValue(":image_date",date("Y-m-d H:i:s"));
        $q->bindValue(":owner_id", $this->owner->id);

        $q->execute();
    }

    public function jsonSerialize(): mixed
    {
        return ["name" => $this->name, "is_public" => $this->is_public, "user" => $this->owner->id];
    }
}

function generateRandomString($length = 16)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }

    return $randomString;
}
