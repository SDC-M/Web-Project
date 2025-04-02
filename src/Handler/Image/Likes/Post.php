<?php

namespace Kuva\Handler\Image\Likes;

use Kuva\Backend\Image;
use Kuva\Backend\Likes;
use Kuva\Backend\User;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class Post extends Handler {
    public function handle(Request $req): void
    {
        $image_id = $req->extracts["image_id"];
        $user = User::getFromSession();

        if ($user == null) {
            $this->response = new Response(403);
            return;
        }

        $image = Image::getById($image_id);

        if (!$image->isOwnedBy($user)) {
            $this->response = new Response(404);
            return;
        }

        if (!Likes::create($user, $image)) {
            $this->response = new Response(500);   
        }

        $this->response = new Response(200);
    }
}
