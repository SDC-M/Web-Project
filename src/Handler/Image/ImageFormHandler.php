<?php

namespace Kuva\Handler\Image;

use Kuva\Backend\Image;
use Kuva\Backend\Middleware\FormMiddleware;
use Kuva\Backend\Middleware\UserMiddleware;
use Kuva\Utils\FormValidator;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class ImageFormHandler extends Handler
{
    public function handle(Request $req): void
    {
        $form = FormMiddleware::validate((new FormValidator())
            ->addFileField("image")
            ->addOptionalTextField("description")
            ->addCheckBoxField("is_public"));
        
        $user = UserMiddleware::getFromSession();

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
