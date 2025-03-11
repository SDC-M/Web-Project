<?php

namespace Kuva\Handler\Image;

use Kuva\Backend\Images;
use Kuva\Backend\User;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class GetByHandler extends Handler {
    public function handle(Request $req): void
    {
        $user_id = User::getById($req->extracts["id"]);

        if ($user_id == null) {
            $this->response = new Response(400, "The targeted user doesn't exists");
            return;
        }

        $session_user = User::getFromSession();


         echo   Images::getPublicImagesOf($user_id)->jsonify();
    }
    
}
