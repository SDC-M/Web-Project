<?php

namespace Kuva\Handler\Image;

use Kuva\Backend\Categories;
use Kuva\Backend\Middleware\ImageMiddleware;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\JsonResponse;
use Kuva\Utils\Router\Request;

class GetCategorieOfImageHandler extends Handler
{
    public function handle(Request $req): void
    {
        $image = ImageMiddleware::getFromUrlAndCheckVisibilityForUser($req);
        $categories = Categories::ofImage($image);
        $this->response = new JsonResponse(200, $categories);
    }
}
