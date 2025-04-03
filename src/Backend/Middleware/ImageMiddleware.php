<?php

namespace Kuva\Backend\Middleware;

use Kuva\Backend\Image;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class ImageMiddleware {
    public static function getFromUrl(Request $req): Image {
        $image_id = $req->extracts["image_id"];
        if (!isset($req->extracts["image_id"])) {
            throw new MiddlewareException(new Response(403, "Bad request"));
        }

        $image = Image::getById($image_id);

        if ($image == null) {
            throw new MiddlewareException(new Response(403, "Bad request")); 
        }
    }
}
