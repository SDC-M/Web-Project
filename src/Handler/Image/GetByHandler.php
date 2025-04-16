<?php

namespace Kuva\Handler\Image;

use Kuva\Backend\Images;
use Kuva\Backend\Middleware\UserMiddleware;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\JsonResponse;
use Kuva\Utils\Router\Request;

class GetByHandler extends Handler
{
    public function handle(Request $req): void
    {

        $session_user = UserMiddleware::getFromSession();
        $user_id = UserMiddleware::getFromUrl($req);

        if ($session_user->id == $user_id->id) {
            $this->response = new JsonResponse(body: Images::getAllImagesOf($user_id));
            return;
        }

        $this->response = new JsonResponse(body: Images::getPublicImagesOf($user_id));
    }

}
