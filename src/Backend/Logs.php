<?php

namespace Kuva\Backend;

use DateTime;
use Exception;
use JsonSerializable;
use Kuva\Backend\Database;
use Kuva\Backend\User;
use PDO;

class Logs implements JsonSerializable {
    private function __construct(private ?int $id, private string $description,
    private DateTime $datetime, private ?User $user)
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

    private static function fromRow(array $row): ?static {
        return new Logs($row['id'], $row['description'], new DateTime($row['creation_date']), User::getById($row['executed_by']));
    }

    public static function getFirstsLogs(): array {
        return self::getLogsAfter(0);
    }


    public static function getLogsAfter(int $id): array {
        $db = new Database();
        $q = $db->db->prepare("SELECT * FROM logs WHERE id > :id ORDER BY logs.creation_date DESC LIMIT 50;");
        $q->bindValue("id", $id);
        try {
           $q->execute();
           return array_map(fn ($r) => self::fromRow($r), $q->fetchAll(PDO::FETCH_ASSOC));
        } catch (Exception $e) {
           return [];
        }
    }

    public function jsonSerialize(): mixed
    {
        return ["id" => $this->id, "description" => $this->description, "creation_date" => $this->datetime->format(DATE_ATOM), "executed_by" => $this->user->id];
    }
    
}
