<?php

namespace Kuva\Handler\Image;

use Kuva\Backend\Logs;
use Kuva\Backend\Middleware\ImageMiddleware;
use Kuva\Backend\Middleware\UserMiddleware;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class PutPermissionHandler extends Handler
{
    public function handle(Request $req): void
    {
        $user = UserMiddleware::getFromSession();
        $image = ImageMiddleware::getFromUrl($req);

        if ($user->id != $image->owner->id) {
            $this->response = new Response(403, "You are not owner of this image");
        }

        $is_public = ($_POST["is_public"] ?? '') == 'true';
        $is_public ? $image->makePublic() : $image->makePrivate();

        if ($is_public) {
            Logs::create_with("User {$user->id} set visibility of image({$image->getId()}) to public", $user);
        } else {
            Logs::create_with("User {$user->id} set visibility of image({$image->getId()}) to private", $user);
        }
    }
}
