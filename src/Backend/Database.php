<?php

namespace Kuva\Backend;

use PDO;

class Database
{
    private const ip = 'mariadb';

    private const port = '3306';

    private const username = 'root';

    private const password = 'root';

    private const db_name = 'kuva';

    public readonly PDO $db;

    public function __construct()
    {
        $this->db = new PDO('mysql:host='.self::ip.':'.self::port.';dbname='.self::db_name, self::username, self::password);
    }
}
