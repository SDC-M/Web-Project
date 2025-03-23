<?php

namespace Kuva\Handler\Image;

use Kuva\Backend\Images;
use Kuva\Backend\User;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class GetByHandler extends Handler
{
    public function handle(Request $req): void
    {
        $user_id = User::getById($req->extracts["id"]);

        if ($user_id == null) {
            $this->response = new Response(400, "The targeted user doesn't exists");
            return;
        }

        $session_user = User::getFromSession();
        if ($session_user != null && $session_user->id == $user_id->id) {
            $this->response = new Response(200, Images::getAllImagesOf($user_id)->jsonify(), ["Content-Type" => "application/json"]);
            return;
        }

        $this->response = new Response(200, Images::getPublicImagesOf($user_id)->jsonify(), ["Content-Type" => "application/json"]);
    }

}
