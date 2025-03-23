<?php

namespace Kuva\Handler;

use Kuva\Backend\Image;
use Kuva\Backend\User;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class DeleteImageHandler extends Handler
{
    public function handle(Request $req): void
    {
        $this->response = new Response(400);

        $image_id = $req->extracts["image_id"];

        $img = Image::getById($image_id);

        if ($img === null) {
            return;
        }

        $user_id = User::getFromSession()?->id;

        if ($user_id === null || $img->owner->id != $user_id) {
            return;
        }

        if ($img->delete() === true) {
            $this->response = new Response(200);
        }

    }
}
