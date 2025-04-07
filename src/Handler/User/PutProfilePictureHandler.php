<?php

namespace Kuva\Handler\User;

use Kuva\Backend\Middleware\FormMiddleware;
use Kuva\Backend\Middleware\UserMiddleware;
use Kuva\Utils\FormValidator;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class PutProfilePictureHandler extends Handler
{
    public function handle(Request $req): void
    {
        $user = UserMiddleware::getFromSession();
        $form = FormMiddleware::validate((new FormValidator())->addFileField("profile_picture"));

        if (!$user->setProfilePicture(file_get_contents($form["profile_picture"]["tmp_name"]))) {
            $this->response = new Response(500);
            return;
        }

        $this->response = new Response(200);
    }
}
