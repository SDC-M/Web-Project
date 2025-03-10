<?php

namespace Kuva\Utils;

class SessionVariable
{
    public function __construct()
    {
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }
    }


    public function setUserId(int $id): void
    {
        $_SESSION["user_id"] = $id;
    }

    public function getUserId(): ?int
    {
        if (!isset($_SESSION["user_id"])) {
            return null;
        }

        return $_SESSION["user_id"];
    }
}
