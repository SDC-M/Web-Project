<?php

namespace Kuva\Backend;

use PDO;

class Images
{
    public function __construct(public array $image)
    {
    }

    public static function getPublicImagesOf(User $user): static
    {
        $db = new Database();

        $q = $db->db->prepare('SELECT id FROM images WHERE user_id = :id AND is_public = TRUE');
        $q->bindValue("id", $user->id);
        $q->execute();
        $aa = [];

        foreach ($q->fetchAll(PDO::FETCH_ASSOC) as $line) {
            $aa[] = Image::getById($line["id"]);
        }

        return new static($aa);
    }

    public static function getAllImagesOf(User $user): static
    {
        $db = new Database();

        $q = $db->db->prepare('SELECT id FROM images WHERE user_id = :id');
        $q->bindValue("id", $user->id);
        $q->execute();
        $aa = [];

        foreach ($q->fetchAll(PDO::FETCH_ASSOC) as $line) {
            $aa[] = Image::getById($line["id"]);
        }

        return new static($aa);
    }

    public static function getLatestPublicImage(): static
    {
        $db = new Database();

        $q = $db->db->prepare('SELECT id FROM images WHERE is_public ORDER BY image_date DESC LIMIT 50 ');
        $q->execute();
        $images = [];

        foreach ($q->fetchAll(PDO::FETCH_ASSOC) as $line) {
            // Could be replace by a join
            $images[] = Image::getById($line["id"]);
        }

        return new static($images);
    }


    public function jsonify(): string
    {
        return json_encode($this->image);
    }
}
