<?php

namespace Kuva\Handler\User;

use Kuva\Backend\User;
use Kuva\Utils\FormValidator;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;
use Kuva\Utils\SessionVariable;

class PostBiographyHandler extends Handler
{
    public function handle(Request $req): void
    {
        $form = (new FormValidator())
            ->addFileField("image")
            ->addTextField("username")
            ->addTextField("biography")
            ->addTextField("password")
            ->validate();

        if ($form === false) {
            $this->response = new Response(400, "Bad form");
            return;
        }


        $user_id = (new SessionVariable())->getUserId() ?? -1;
        $user = User::getById($user_id);
        if ($user_id === null) {
            $this->response = new Response(403, "You aren't connected");
            return;
        }

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
