<?php

namespace Kuva\Handler\Image;

use Kuva\Backend\Images;
use Kuva\Backend\User;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class ListUserImageHandler extends Handler
{
    public function handle(Request $req): void
    {
        $this->response = new Response(400);
        $user = User::getFromSession();
        if ($user === null) {
            return;
        }

        $images = Images::getAllImagesOf($user);


        if ($images === null) {
            return;
        }

        $this->response = new Response(200, $images->jsonify(), ['Content-Type' => 'application/json']);
    }
}
