<?php

namespace Kuva\Backend;

use PDO;

class Images {
    public function __construct(public array $image)
    {
    }

    public static function getPublicImagesOf(User $user): static {
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

    public function jsonify(): string {
        return json_encode($this->image);
    }
}

