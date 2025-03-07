<?php
namespace Kuva\Backend;

use PDO;

class Database {
    private const ip = '127.0.0.1';
    private const port = '3306';
    private const username = "root";
    private const password = "root";
    private const db_name = "kuva";

    private PDO $db;

    public function __construct()
    {
        $this->db = new PDO("mysql:host=". self::ip . ":" . self::port . ";dbname=" . self::db_name, self::username, self::password);
    }

    public function getUserIdByNameAndPassword(string $username, string $password): int {
        $q = $this->db->prepare('SELECT id FROM users WHERE username = :username and password = :pass');
        $q->bindParam('username', $username);
        $q->bindParam('pass', $password);
        $q->execute();
        return $q->fetch(PDO::FETCH_ASSOC)["id"];
    }
}
