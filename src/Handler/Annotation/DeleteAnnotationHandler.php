<?php

namespace Kuva\Handler\Annotation;

use Kuva\Backend\Annotation;
use Kuva\Backend\User;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class DeleteAnnotationHandler extends Handler {
    public function handle(Request $req): void
    {
        $this->response = new Response(400);

        $annotation_id = $req->extracts['image_id'] ?? -1;
        $annotation = Annotation::getById($annotation_id);        
        if ($annotation == null) {
            return;
        }

        $user = User::getFromSession();

        if ($user == null) {
            return;
        }

        $annotation->delete();
    }
}
