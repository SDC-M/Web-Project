<?php

namespace Kuva\Handler\Annotation;

use Kuva\Backend\Annotation;
use Kuva\Backend\Image;
use Kuva\Backend\Middleware\FormMiddleware;
use Kuva\Backend\Middleware\UserMiddleware;
use Kuva\Utils\FormValidator;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class AnnotationFormHandler extends Handler
{
    public function handle(Request $req): void
    {

        $this->response = new Response(400);
        $image_id = $req->extracts["image_id"] ?? -1;
        $image = Image::getById($image_id);
        if ($image == null) {
            return;
        }

        $form = FormMiddleware::validate((new FormValidator())->addTextField("description")
            ->addTextField("x1")
            ->addTextField("x2")
            ->addTextField("y1")
            ->addTextField("y2"));

        $user = UserMiddleware::getFromSession();

        $annotation = Annotation::createWithUserAndImage($image, $user);
        $annotation->setDescription($form["description"]);
        $annotation->setFirstPoint($form["x1"], $form["y1"]);
        $annotation->setSecondPoint($form["x2"], $form["y2"]);
        if ($annotation->addToDatabase() === false) {
            $this->response = new Response(500);
        }

        $this->response = new Response(200, headers: ["Location" => "/annotations/{$user->id}/{$image_id}"]);
    }
}
