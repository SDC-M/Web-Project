<?php

namespace Kuva\Handler\Image;

use Kuva\Backend\Images;
use Kuva\Backend\Middleware\UserMiddleware;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\JsonResponse;
use Kuva\Utils\Router\Request;

class ListUserImageHandler extends Handler
{
    public function handle(Request $req): void
    {
        $user = UserMiddleware::getFromSession();
        $images = Images::getAllImagesOf($user);

        if ($images === null) {
            return;
        }

        $this->response = new JsonResponse(body: $images);
    }
}
