<?php

namespace Kuva\Handler\Image;

use Kuva\Backend\Logs;
use Kuva\Backend\Middleware\FormMiddleware;
use Kuva\Backend\Middleware\ImageMiddleware;
use Kuva\Backend\Middleware\UserMiddleware;
use Kuva\Utils\FormValidator;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class PutDescriptionOfImageHandler extends Handler
{
    public function handle(Request $req): void
    {
        $image = ImageMiddleware::getFromUrl($req);
        $user = UserMiddleware::getFromSession();
        $description = FormMiddleware::validate((new FormValidator())->addTextField("description"));

        if ($user->id != $image->owner->id) {
            if ($image->is_public) {
                $this->response = new Response(403, "Not enough permission");
                return;
            }


            $this->response = new Response(404);
            return;
        }

        if (!$image->setDescription($description["description"])) {
            $this->response = new Response(500);
            return;
        }

        Logs::create_with("User {$user->id} updated description of image({$image->getId()})", $user);

        $this->response = new Response(200);
    }
}
