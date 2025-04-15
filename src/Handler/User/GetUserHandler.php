<?php

namespace Kuva\Handler\User;

use Kuva\Backend\Middleware\UserMiddleware;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\JsonResponse;
use Kuva\Utils\Router\Request;

class GetUserHandler extends Handler
{
    public function handle(Request $req): void
    {
        $user = UserMiddleware::getFromUrl($req);

        $this->response = new JsonResponse(body: $user);
    }
}
