<?php

namespace Kuva\Backend;

use Exception;
use Kuva\Utils\SessionVariable;
use PDO;

class User
{
    private function __construct(
        public string $id,
        public readonly string $username,
        public readonly string $email,
        public readonly string $recovery
    ) {
    }

    public static function getByNameAndPassword(string $name, string $password): ?static
    {
        $db = new Database();
        $q = $db->db->prepare('SELECT id, email, recovery_key FROM users WHERE username = :username and password = :pass');
        $q->bindParam('username', $name);
        $q->bindParam('pass', $password);
        $q->execute();
        $values = $q->fetch(PDO::FETCH_ASSOC);
        if ($values === false) {
            return null;
        }

        return new static($values['id'], $name, $values["email"], $values["recovery_key"]);
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

    public static function getById(int $id): ?static
    {
        $db = new Database();
        $q = $db->db->prepare('SELECT username, email FROM users WHERE id = :id');
        $q->bindParam(":id", $id);
        try {
            $q->execute();
        } catch (Exception $ex) {
            return null;
        }
        $values = $q->fetch(PDO::FETCH_ASSOC);
        // TODO: Add recovery key
        if ($values === false) {
            return null;
        }

        return new static($id, $values["username"], $values["email"], "");
    }

    public static function getByNameAndRecoverykey(string $name, string $recovery): ?static
    {
        $db = new Database();
        $q = $db->db->prepare('SELECT id, email, recovery_key FROM users WHERE username = :username and recovery_key = :pass');
        $q->bindParam('username', $name);
        $q->bindParam('pass', $recovery);
        $q->execute();
        $values = $q->fetch(PDO::FETCH_ASSOC);
        if ($values === false) {
            return null;
        }

        return new static($values['id'], $name, $values["email"], $values["recovery_key"]);
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

    public function updatePassword(string $password)
    {
        $db = new Database();
        $q = $db->db->prepare('UPDATE users SET password = :password WHERE id = :id');
        $q->bindParam('password', $password);
        $q->bindParam('id', $this->id);
        try {
            return $q->execute();
        } catch (Exception $ex) {
            var_dump($ex);

            return false;
        }
    }
}
