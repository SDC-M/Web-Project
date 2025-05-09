<?php

namespace Kuva\Handler\Image;

use Kuva\Backend\Middleware\ImageMiddleware;
use Kuva\Backend\User;
use Kuva\Utils\Router\FileBody;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class GetImageHandler extends Handler
{
    public function handle(Request $req): void
    {
        $image = ImageMiddleware::getFromUrl($req);


        if (!$image->is_public) {
            $user = User::getFromSession();
            if ($user == null || $user->id != $image->owner->id) {
                $req = new Response(404);
                return;
            }
        }

        $this->response = new Response(200, new FileBody($image->getPath()), ["Content-Type" => "image/png"]);
    }

}
