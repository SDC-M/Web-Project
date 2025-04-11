<?php

namespace Kuva\Backend\Middleware;

use Kuva\Backend\Categories;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class CategoriesMiddleware {   
    public static function getFromUrl(Request $req): Categories
    {
        $categorie_id = $req->extracts["categorie_id"] ?? -1;
        $categorie = Categories::getById($categorie_id);

        if ($categorie === null) {
            throw new MiddlewareException(new Response(404, "This categorie doesn't exist"));
        }

        return $categorie;
    }
}
