<?php

namespace Kuva\Backend\Middleware;

use Kuva\Utils\FormValidator;
use Kuva\Utils\Router\Response;

class FormMiddleware {
    /**
     * @return array<string, mixed>
     */
    public static function validate(FormValidator $fv): array
    {

        $values = $fv->validate();
        if ($values === false) {
            throw new MiddlewareException(new Response(400, "Bad request"));
        }

        return $values;
    }
}
