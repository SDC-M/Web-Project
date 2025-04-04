<?php

namespace Kuva\Handler\Annotation;

use Kuva\Backend\Annotation;
use Kuva\Backend\Middleware\ImageMiddleware;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\JsonResponse;
use Kuva\Utils\Router\Request;

class GetAnnotationHandler extends Handler
{
    public function handle(Request $req): void
    {
        $image = ImageMiddleware::getFromUrl($req);
        $this->response = new JsonResponse(body: Annotation::getAllOfImage($image));
    }
}
