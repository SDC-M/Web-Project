<?php

namespace Kuva\Backend;

use DateTime;
use Exception;
use JsonSerializable;
use PDO;

class Logs implements JsonSerializable
{
    private function __construct(
        private ?int $id,
        private string $description,
        private DateTime $datetime,
        private ?User $user
    ) {
    }

    public static function create_with(string $description, ?User $user = null): bool
    {
        $db = new Database();
        $q = $db->db->prepare("INSERT INTO logs (description,creation_date,executed_by)
                               VALUES (:description,:creation_date,:executed_by)");
        $q->bindValue(":description", $description);
        $q->bindValue(":executed_by", $user->id);
        $q->bindValue(":creation_date", date("Y-m-d H:i:s"));
        try {
            return $q->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    private static function fromRow(array $row): ?static
    {
        return new Logs($row['id'], $row['description'], new DateTime($row['creation_date']), User::getById($row['executed_by'] ?? -1));
    }

    public static function getFirstsLogs(): array
    {
        $db = new Database();
        $q = $db->db->prepare("SELECT * FROM kuva.logs ORDER BY kuva.logs.id DESC LIMIT 5;");
        try {
            $q->execute();
            return array_map(fn ($r) => self::fromRow($r), $q->fetchAll(PDO::FETCH_ASSOC));
        } catch (Exception $e) {
            return [];
        }
    }


    public static function getLogsBefore(int $id): array
    {
        $db = new Database();
        $q = $db->db->prepare("SELECT * FROM kuva.logs WHERE id < :id ORDER BY kuva.logs.id DESC LIMIT 5;");
        $q->bindValue("id", $id);
        try {
            $q->execute();
            return array_map(fn ($r) => self::fromRow($r), $q->fetchAll(PDO::FETCH_ASSOC));
        } catch (Exception $e) {
            return [];
        }
    }

    public static function getFirstsLogsByExecutor(User $user): array
    {
        $db = new Database();
        $q = $db->db->prepare("SELECT * FROM kuva.logs WHERE executed_by = :user ORDER BY kuva.logs.id DESC LIMIT 5;");
        $q->bindValue("user", $user->id);
        try {
            $q->execute();
            return array_map(fn ($r) => self::fromRow($r), $q->fetchAll(PDO::FETCH_ASSOC));
        } catch (Exception $e) {
            return [];
        }
    }

    public static function getLogsByExecutorBefore(int $id, User $user): array
    {
        $db = new Database();
        $q = $db->db->prepare("SELECT * FROM kuva.logs WHERE id < :id AND executed_by = :user ORDER BY kuva.logs.id DESC LIMIT 5;");
        $q->bindValue("id", $id);
        $q->bindValue("user", $user->id);
        try {
            $q->execute();
            return array_map(fn ($r) => self::fromRow($r), $q->fetchAll(PDO::FETCH_ASSOC));
        } catch (Exception $e) {
            return [];
        }
    }


    public function jsonSerialize(): mixed
    {
        return ["id" => $this->id, "description" => $this->description, "creation_date" => $this->datetime->format(DATE_ATOM), "executed_by" => $this->user?->id];
    }

}
