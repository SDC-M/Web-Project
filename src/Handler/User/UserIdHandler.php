<?php

namespace Kuva\Handler\User;

use Kuva\Backend\Middleware\UserMiddleware;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\JsonResponse;
use Kuva\Utils\Router\Request;

class UserIdHandler extends Handler
{
    public function handle(Request $req): void
    {
        $user = UserMiddleware::getFromSession();
        $this->response = new JsonResponse(body: ['id' => $user->id]);
    }
}
