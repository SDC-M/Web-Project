<?php

namespace Kuva\Handler\Image;

use Kuva\Backend\Middleware\ImageMiddleware;
use Kuva\Backend\Middleware\UserMiddleware;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\JsonResponse;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class GetDescriptionOfImageHandler extends Handler
{
    public function handle(Request $req): void
    {
        $image = ImageMiddleware::getFromUrl($req);
        $user = UserMiddleware::getFromSession();

        if ($image->is_public === 1 && $user != $image->owner->id) {
            $this->response = new Response(404);
            return;
        }

        $this->response = new JsonResponse(body: ["description" => $image->description]);
    }
}
