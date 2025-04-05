<?php

namespace Kuva\Handler\Image\Likes;

use Kuva\Backend\Likes;
use Kuva\Backend\Middleware\ImageMiddleware;
use Kuva\Backend\Middleware\UserMiddleware;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class Delete extends Handler
{
    public function handle(Request $req): void
    {
        $user = UserMiddleware::getFromSession();
        $image = ImageMiddleware::getFromUrl($req);

        Likes::get($user, $image)->delete();
        $this->response = new Response(200);
    }
}
