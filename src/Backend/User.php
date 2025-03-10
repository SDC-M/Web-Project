<?php

namespace Kuva\Backend;

use Exception;
use Kuva\Utils\SessionVariable;
use PDO;

class User
{
    private function __construct(
        public readonly string $id,
        public readonly string $username,
        public readonly string $email
    ) {
    }

    public static function login(string $name, string $password): ?static
    {
        $db = new Database();
        $q = $db->db->prepare('SELECT id, email FROM users WHERE username = :username and password = :pass');
        $q->bindParam('username', $name);
        $q->bindParam('pass', $password);
        $q->execute();
        $values = $q->fetch(PDO::FETCH_ASSOC);
        if ($values === false) {
            return null;
        }

        return new static($values['id'], $name, $values["email"]);
    }

    public static function register(string $name, string $email, string $password, string $recovery_answer): bool
    {
        $db = new Database();
        $q = $db->db->prepare('INSERT INTO users(username, email, password, recovery_key) VALUES (:name, :email, :password, :answer)');
        $q->bindParam('name', $name);
        $q->bindParam('email', $email);
        $q->bindParam('password', $password);
        $q->bindParam('answer', $recovery_answer);
        try {
            return $q->execute();
        } catch (Exception $ex) {
            var_dump($ex);

            return false;
        }
    }

    public static function getById(int $id): static
    {
        $db = new Database();
        $q = $db->db->prepare('SELECT username, email FROM users WHERE id = :id');
        $q->bindParam(":id", $id);
        $q->execute();
        $values = $q->fetch(PDO::FETCH_ASSOC);

        return new static($id, $values["username"], $values["email"]);
    }

    public static function getFromSession(): ?static
    {
        $session = new SessionVariable();
        $id = $session->getUserId();

        if ($id == null) {
            return null;
        }

        return User::getById($id);
    }
}
