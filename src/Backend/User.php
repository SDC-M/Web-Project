<?php
namespace Kuva\Backend;

use Kuva\Backend\Database;

class User {
    private function __construct(protected string $id, string $username, string $email)
    {}
    
    public static function login(string $name, string $password): static|null {
        $db = new Database();
        $id = $db->getUserIdByNameAndPassword($name, $password);
        if ($id == null) {
            return null;
        }
        return new static($id, $name, $password);
    }
}
