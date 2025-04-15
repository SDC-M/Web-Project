<?php

namespace Kuva\Backend\Middleware;

use Kuva\Backend\Image;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class ImageMiddleware
{
    const IMAGE_NOT_FOUND = "This image doesn't exist";
    
    public static function getFromUrl(Request $req): Image
    {
        $image_id = $req->extracts["image_id"] ?? '';

        if (filter_var($image_id, FILTER_VALIDATE_INT) === false) {
            throw new MiddlewareException(new Response(404, self::IMAGE_NOT_FOUND));
        }

        $image = Image::getById($image_id);

        if ($image == null) {
            throw new MiddlewareException(new Response(404, self::IMAGE_NOT_FOUND));
        }

        return $image;
    }


    public static function getFromUrlAndCheckVisibilityForUser(Request $req): Image
    {
        $image = self::getFromUrl($req);
        $user = UserMiddleware::getFromSession();

        if (!$image->is_public && $image->owner->id != $user->id) {
            throw new MiddlewareException(new Response(404, self::IMAGE_NOT_FOUND));
        }

        return $image;
    }
}
