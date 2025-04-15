<?php

namespace Kuva\Handler\User;

use Kuva\Backend\Logs;
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
            ->addTextFieldWithMaxLength("username", 100)
            ->addTextField("biography", 250)
            ->addTextField("password")
            ->addFileField("profile_picture"));

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

        if (!$user->setProfilePicture(file_get_contents($form["profile_picture"]["tmp_name"]))) {
            return;
        }        

        Logs::create_with("User {$user->id} change his profile picture", $user);
        Logs::create_with("User {$user->id} change his description", $user);

        $this->response = new Response(301, "", ["Location" => "/profile"]);
    }
}
