<?php

namespace Kuva\Handler;

use Kuva\Backend\Image;
use Kuva\Backend\User;
use Kuva\Utils\FormValidator;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;
use Kuva\Utils\SessionVariable;

class ImageFormHandler extends Handler
{
    public function handle(Request $req): void
    {
        // Set by default, error response
        $this->response = new Response(400);

        $form = (new FormValidator())
            ->addFileField("image")
            ->addOptionalTextField("description")
            ->addCheckBoxField("is_public")
            ->validate();

        if ($form === false) {
            $this->response = new Response(400, "Bad form");
            return;
        }


        $user_id = (new SessionVariable())->getUserId() ?? -1;
        $user = User::getById($user_id);

        if ($user == null) {
            $this->response = new Response(401, "Connect you plz");
            return;
        }
        if ($form["image"]["error"] != 0) {
            $this->response = new Response(413, "Problem when handling image (Probably too large)");
            return;
        }

        $image = Image::fromFile($form["image"]["tmp_name"]);
        $image->description = $form["description"];
        $image->is_public = $form["is_public"];
        $image->linkTo($user);
        $image->commit();

        $this->response = new Response(200, headers: ["Location" => "/profile"]);
    }
}
