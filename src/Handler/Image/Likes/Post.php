<?php

namespace Kuva\Handler\Image\Likes;

use Kuva\Backend\Likes;
use Kuva\Backend\Logs;
use Kuva\Backend\Middleware\ImageMiddleware;
use Kuva\Backend\Middleware\UserMiddleware;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class Post extends Handler
{
    public function handle(Request $req): void
    {
        $user = UserMiddleware::getFromSession();
        $image = ImageMiddleware::getFromUrl($req);

        if (!$image->is_public && !$image->isOwnedBy($user)) {
            $this->response = new Response(404);
            return;
        }

        if (!Likes::create($user, $image)) {
            $this->response = new Response(500);
        }

        Logs::create_with("User {$user->id} a like to {$image->getId()}", $user);
        
        $this->response = new Response(200);
    }
}
