<?php

namespace Kuva\Handler\Annotation;

use Kuva\Backend\Logs;
use Kuva\Backend\Middleware\AnnotationMiddleware;
use Kuva\Backend\Middleware\UserMiddleware;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class DeleteAnnotationHandler extends Handler
{
    public function handle(Request $req): void
    {
        $annotation = AnnotationMiddleware::getFromUrl($req);
        $user = UserMiddleware::getFromSession();
        $image = $annotation->image;

        if (!$image->isOwnedBy($user) && $annotation->user->id != $user->id) {
            $this->response = new Response(400, "You aren't the owner of this annotation !");
            return;
        }

        if ($annotation->delete() === false) {
            $this->response = new Response(500, "Cannot delete");
            return;
        }

        Logs::create_with("User {$user->id} deleted an annotation ({$annotation->id}", $user);

        $this->response = new Response(200);
    }
}
