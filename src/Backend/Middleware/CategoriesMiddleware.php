<?php

namespace Kuva\Backend\Middleware;

use Kuva\Backend\Categories;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class CategoriesMiddleware
{
    public static function getFromUrl(Request $req): Categories
    {
        $category_name = $req->extracts["category_name"] ?? '';
        $category = Categories::getByName($category_name);

        if ($category === null) {
            throw new MiddlewareException(new Response(404, "This category doesn't exist"));
        }

        return $category;
    }
}
