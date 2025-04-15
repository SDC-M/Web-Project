<?php

namespace Kuva\Backend\Middleware;

use Kuva\Backend\Annotation;
use Kuva\Backend\Image;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class AnnotationMiddleware
{
    const ANNOTATION_NOT_FOUND = "This annotation doesn't exist";
    
    public static function getFromUrl(Request $req): Annotation
    {
        $annotation_id = $req->extracts["annotation_id"] ?? '';

        if (filter_var($annotation_id, FILTER_VALIDATE_INT) === false) {
            throw new MiddlewareException(new Response(404, self::ANNOTATION_NOT_FOUND));
        }

        $annotation = Annotation::getById($annotation_id);

        if ($annotation == null) {
            throw new MiddlewareException(new Response(404, self::ANNOTATION_NOT_FOUND));
        }

        return $annotation;
    }
}
