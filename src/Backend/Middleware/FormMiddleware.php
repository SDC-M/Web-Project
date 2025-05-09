<?php

namespace Kuva\Backend\Middleware;

use Exception;
use Kuva\Utils\FormValidator;
use Kuva\Utils\Router\Response;

class FormMiddleware
{
    /**
     * @return array<string, mixed>
     */
    public static function validate(FormValidator $fv): array
    {
        try {
            $values = $fv->validate();
        } catch (Exception $e) {
            throw new MiddlewareException(new Response(400, "Bad request : {$e->getMessage()} is badly set"));
        }
        return $values;
    }
}
