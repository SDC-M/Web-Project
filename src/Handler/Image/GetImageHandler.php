<?php

namespace Kuva\Handler\Image;

use Kuva\Backend\Middleware\ImageMiddleware;
use Kuva\Backend\Middleware\UserMiddleware;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class GetImageHandler extends Handler
{
    public function handle(Request $req): void
    {
        $image = ImageMiddleware::getFromUrl($req);
        $user = UserMiddleware::getFromSession();

        if (!$image->is_public && $user != $image->owner->id) {
            return;
        }

        $this->response = new Response(200, $image->getBytes(), ["Content-Type" => "image/png"]);
    }

}
