<?php

namespace Kuva\Handler\Image;

use Kuva\Backend\Middleware\ImageMiddleware;
use Kuva\Backend\Middleware\UserMiddleware;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\JsonResponse;
use Kuva\Utils\Router\Request;

class GetDetailsHandler extends Handler {
    public function handle(Request $req): void
    {
        $user = UserMiddleware::getFromSession();
        $image = ImageMiddleware::getFromUrlAndCheckVisibilityForUser($req, $user);

        $this->response = new JsonResponse(200, $image);
        
    }
}
