<?php

namespace Kuva\Backend;

use AssertionError;

class Image
{
    public const string IMAGE_FOLDER = "../images/";


    private function __construct(
        public readonly ?string $name,
        public readonly ?User $owner,
        public array $bytes
    ) {
    }

    public static function fromBytes(array $bytes): static
    {
        return new static(generateRandomString() . ".image", null, $bytes);
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
        $path = self::IMAGE_FOLDER . $this->owner->id . '/' . $this->name;
        file_put_contents($path, $this->bytes);
    }

    private function addToDatabase(): void
    {
        $db = new Database();
        $q = $db->db->prepare('INSERT INTO images(file_path, description, is_public, image_date, user_id)'
        . 'VALUES (:file_path, :descripton, :is_public, :image_date, :owner_id)');

        $q->bindParam(":file_path", $this->name);
        $q->bindParam(":description", "blah");
        $q->bindParam(":is_public", false);
        $q->bindParam(":image_date", "10-02-2024 13:30:30");
        $q->bindParam(":owner_id", $this->owner->id);

        $q->execute();
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
