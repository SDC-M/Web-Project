<?php

namespace Kuva\Handler\Categories;

use Kuva\Backend\Middleware\CategoriesMiddleware;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\JsonResponse;
use Kuva\Utils\Router\Request;

class GetCategories extends Handler
{
    public function handle(Request $req): void
    {
        $categories = CategoriesMiddleware::getFromUrl($req);
        $this->response = new JsonResponse(200, $categories);
    }
}
