<?php

namespace Kuva\Handler\User;


use Kuva\Backend\Middleware\UserMiddleware;
use Kuva\Backend\User;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class GetProfilePictureHandler extends Handler
{
    public function handle(Request $req): void
    {
        $user = UserMiddleware::getFromUrl($req);
        $bytes = $user->getProfilePicture();

        if ($bytes === false) {
            $this->response = new Response(500);
            return;
        }


       $this->response = new Response(200, $bytes, ["Content-Type" => "image/png"]);        
    }
}
