<?php

namespace Kuva\Handler\Image\Likes;

use Kuva\Backend\Likes;
use Kuva\Backend\Middleware\ImageMiddleware;
use Kuva\Backend\Middleware\UserMiddleware;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\JsonResponse;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class Get extends Handler {
    public function handle(Request $req): void
    {

        $user = UserMiddleware::getFromSession();
        $image = ImageMiddleware::getFromUrl($req, $user);

        if (!$image->is_public && !$image->isOwnedBy($user)) {
            $this->response = new Response(404);
            return;
        }

        $this->response = new JsonResponse(body: Likes::getAllOfImage($image));
    }
}
