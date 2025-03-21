<?php

namespace Kuva\Handler\Annotation;

use Kuva\Backend\Annotation;
use Kuva\Backend\User;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;
use Kuva\Utils\Validator;

class AnnotationFormHandler extends Handler
{
    public function handle(Request $req): void
    {

        $this->response = new Response(400);

        $description = '';
        $x1 = '';
        $x2 = '';
        $y1 = '';
        $y2 = '';
        $image = null;
        $user = null;

        $form = (new Validator())
            ->getImageFromUrlParam("image_id", $image)
            ->getStringFromFormParam("description", $description)
            ->getStringFromFormParam($x1, "x1")
            ->getStringFromFormParam($x2, "x2")
            ->getStringFromFormParam($y1, "y1")
            ->getStringFromFormParam($y2, "y2")
            ->getUserFromSession($user)
            ->validate();

        if ($form !== null) {
            $this->response = new Response(400, $form);
            return;
        }


        $user = User::getFromSession();
        if ($user === null) {
            return;
        }

        $annotation = Annotation::createWithUserAndImage($image, $user);
        $annotation->setDescription($description);
        $annotation->setFirstPoint($x1, $y1);
        $annotation->setSecondPoint($x2, $y2);
        if ($annotation->addToDatabase() === false) {
            $this->response = new Response(500);
        }

        $this->response = new Response(200, headers: ["Location" => "/annotations/{$user->id}/{$image_id}"]);
    }
}
