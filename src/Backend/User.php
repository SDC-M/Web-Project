<?php
namespace Kuva\Backend;

// require 'vendor/autoload.php';

use Kuva\Backend\Database;

class User {
    private function __construct(protected string $id, string $username, string $email)
    {}
    
    public static function login(string $name): static {
        $db = new Database();
        $db->getUserIdByNameAndPassword("me", "me");
        return new static(0, "", "");
    }
}
