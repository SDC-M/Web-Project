<?php

namespace Kuva\Backend;

use Exception;
use PDO;

class User
{
    private function __construct(protected string $id,
        public readonly string $username,
        public readonly string $email) {}

    public static function login(string $name, string $password): ?static
    {
        $db = new Database;
        $q = $db->db->prepare('SELECT id FROM users WHERE username = :username and password = :pass');
        $q->bindParam('username', $name);
        $q->bindParam('pass', $password);
        $q->execute();
        $id = $q->fetch(PDO::FETCH_ASSOC);
        if ($id === false) {
            return null;
        }

        return new static($id['id'], $name, $password);
    }

    public static function register(string $name, string $email, string $password): bool
    {
        $db = new Database;
        $q = $db->db->prepare('INSERT INTO users(username, email, password) VALUES (:name, :email, :password)');
        $q->bindParam('name', $name);
        $q->bindParam('email', $email);
        $q->bindParam('password', $password);
        try {
            return $q->execute();
        } catch (Exception $ex) {
            return false;
        }
    }
}
