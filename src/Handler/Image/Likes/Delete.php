<?php

namespace Kuva\Handler\Image\Likes;

use Kuva\Backend\Image;
use Kuva\Backend\Likes;
use Kuva\Backend\User;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class Delete extends Handler {
    public function handle(Request $req): void
    {
        $image_id = $req->extracts["image_id"];
        $user = User::getFromSession();

        if ($user == null) {
            $this->response = new Response(403);
            return;
        }

        Likes::get($user, Image::getById($image_id))->delete();
        $this->response = new Response(200);
    }
}
