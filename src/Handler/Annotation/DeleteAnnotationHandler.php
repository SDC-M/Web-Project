<?php

namespace Kuva\Handler\Annotation;

use Kuva\Backend\Annotation;
use Kuva\Backend\Logs;
use Kuva\Backend\User;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class DeleteAnnotationHandler extends Handler
{
    public function handle(Request $req): void
    {
        $this->response = new Response(400);

        $annotation_id = $req->extracts['annotation_id'] ?? -1;
        $annotation = Annotation::getById($annotation_id);
        if ($annotation == null) {
            $this->response->body = "This annotation doesn't exists";
            return;
        }

        $user = User::getFromSession();

        if ($user == null) {
            $this->response->body = "You aren't connected";
            return;
        }

        if ($annotation->user->id != $user->id) {
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
