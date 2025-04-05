<?php

namespace Kuva\Backend\Middleware;

use Kuva\Backend\User;
use Kuva\Utils\Router\Response;
use Kuva\Utils\SessionVariable;

class UserMiddleware
{
    public static function getFromSession(): User
    {
        $session = new SessionVariable();
        $id = $session->getUserId();

        if ($id == null) {
            throw new MiddlewareException(new Response(403, "You need to be logged to use this endpoint"));
        }

        $user = User::getById($id);

        // This should never happened but we never know
        if ($user === null) {
            throw new MiddlewareException(new Response(502, "Internal server error"));
        }

        return $user;
    }
}
