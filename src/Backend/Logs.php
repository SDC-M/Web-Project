<?php

namespace Kuva\Backend;

use DateTime;
use Exception;
use Kuva\Backend\Database;
use Kuva\Backend\User;

class Logs {
    private function __construct(private ?int $id, private string $description,
    private DateTime $datetime, private User $user)
    {
    }

    public static function create_with(string $description,  User $user): bool {
        $db = new Database();
        $q = $db->db->prepare("INSERT INTO logs (description,creation_date,executed_by)
                               VALUES (:description,:creation_date,:executed_by)");
        $q->bindValue(":description", $description);
        $q->bindValue(":executed_by", $user->id);
        $q->bindValue(":creation_date",date("Y-m-d H:i:s"));       
        try {
           return $q->execute();           
        } catch (Exception $e) {
           return false;
        }
    }
}
