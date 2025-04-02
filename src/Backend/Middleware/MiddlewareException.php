<?php

namespace Kuva\Backend\Middleware;

use Exception;
use Kuva\Utils\Router\Response;

class MiddlewareException extends Exception {
    public function __construct(public Response $response)
    {
    }
}
