<?php

namespace Kuva\Handler\Annotation;

use Kuva\Backend\Annotation;
use Kuva\Backend\Logs;
use Kuva\Backend\Middleware\FormMiddleware;
use Kuva\Backend\Middleware\ImageMiddleware;
use Kuva\Backend\Middleware\UserMiddleware;
use Kuva\Utils\FormValidator;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class AnnotationFormHandler extends Handler
{
    public function handle(Request $req): void
    {

        $image = ImageMiddleware::getFromUrl($req);
        $form = FormMiddleware::validate((new FormValidator())->addTextFieldWithMaxLength("description", 4096)
            ->addIntField("x1")
            ->addIntField("x2")
            ->addIntField("y1")
            ->addIntField("y2"));
        $user = UserMiddleware::getFromSession();

        $annotation = Annotation::createWithUserAndImage($image, $user);
        $annotation->setDescription($form["description"]);
        $annotation->setFirstPoint($form["x1"], $form["y1"]);
        $annotation->setSecondPoint($form["x2"], $form["y2"]);
        if ($annotation->addToDatabase() === false) {
            $this->response = new Response(500);
        }

        Logs::create_with("User {$user->id} created an annotation on image({$image->getId()}", $user);

        $this->response = new Response(200);
    }
}
