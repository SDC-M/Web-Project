<?php

namespace Kuva\Handler\User;

use Kuva\Backend\Middleware\FormMiddleware;
use Kuva\Backend\Middleware\UserMiddleware;
use Kuva\Utils\FormValidator;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class PostBiographyHandler extends Handler
{
    public function handle(Request $req): void
    {
        $form = FormMiddleware::validate((new FormValidator())
            ->addFileField("image")
            ->addTextField("username")
            ->addTextField("biography")
            ->addTextField("password"));

        $user = UserMiddleware::getFromSession();

        if (!$user->verifyPassword($form["password"])) {
            $this->response = new Response(400, "Bad password");
            return;
        }

        $this->response = new Response(500, "Cannot update the user");
        if (!$user->updateUsername($form["username"])) {
            return;
        }

        if (!$user->updateBiography($form["biography"])) {
            return;
        }

        $this->response = new Response(301, "", ["Location" => "/profile"]);
    }
}
