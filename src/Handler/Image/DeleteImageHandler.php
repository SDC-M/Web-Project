<?php

namespace Kuva\Handler\Image;

use Kuva\Backend\Logs;
use Kuva\Backend\Middleware\ImageMiddleware;
use Kuva\Backend\Middleware\UserMiddleware;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class DeleteImageHandler extends Handler
{
    public function handle(Request $req): void
    {
        $user = UserMiddleware::getFromSession();
        $image = ImageMiddleware::getFromUrl($req);

        if ($image->owner->id != $user->id) {
            $this->response = new Response(403, "Not enough permission");
            return;
        }

        if ($image->delete() === false) {
            $this->response = new Response(500);
            return;
        }

        Logs::create_with("User {$user->id} deleted an image ({$image->getId()}", $user);
    }
}
