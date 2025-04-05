<?php

namespace Kuva\Backend;

use JsonSerializable;
use PDO;

class Likes implements JsonSerializable
{
    private function __construct(public User $user, private Image $image)
    {
    }

    private static function fromRow(array $d): static
    {
        return new static(User::getById($d["user_id"]), Image::getById($d["image_id"]));
    }

    public static function get(User $user, Image $image): ?static
    {
        $db = (new Database())->db;

        $q = $db->prepare("SELECT image_id, user_id FROM likes WHERE image_id = ? AND user_id = ?");
        $q->execute([$image->getId(), $user->id]);

        if ($q === false) {
            return null;
        }

        $d = $q->fetchAll(PDO::FETCH_ASSOC);

        if (count($d) == 0) {
            return null;
        }

        return static::fromRow($d[0]);
    }


    public static function getAllOfUser(User $user): array
    {
        $db = (new Database())->db;

        $q = $db->prepare("SELECT image_id, user_id FROM likes WHERE user_id = ?");
        $q->execute([$user->id]);

        if ($q === false) {
            return null;
        }

        $d = $q->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn ($d): static => static::fromRow($d), $d);

    }


    public static function getAllOfImage(Image $image): array
    {
        $db = (new Database())->db;

        $q = $db->prepare("SELECT image_id, user_id FROM likes WHERE image_id = ?");
        $q->execute([$image->getId()]);

        if ($q === false) {
            return null;
        }

        $d = $q->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn ($d): static => static::fromRow($d), $d);
    }

    public function delete(): bool
    {
        $db = (new Database())->db;

        $q = $db->prepare("DELETE FROM kuva.likes WHERE image_id= ? AND user_id= ?");
        $q->execute([$this->image->getId(), $this->user->id]);

        if ($q === false) {
            return null;
        }

        return true;
    }

    public static function create(User $user, Image $image): bool
    {
        $db = (new Database())->db;

        $q = $db->prepare("INSERT INTO kuva.likes(image_id, user_id) VALUES(?, ?)");
        $q->execute([$image->getId(), $user->id]);

        if ($q === false) {
            return null;
        }

        return true;

    }

    public function jsonSerialize(): mixed
    {
        return $this->user;
    }
}
