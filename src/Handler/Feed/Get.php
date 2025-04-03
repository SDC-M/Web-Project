<?php

namespace Kuva\Handler\Feed;

use Kuva\Backend\Images;
use Kuva\Backend\Middleware\UserMiddleware;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\JsonResponse;
use Kuva\Utils\Router\Request;

class Get extends Handler
{
    public function handle(Request $req): void
    {
        $user = UserMiddleware::getFromSession();
        $this->response = new JsonResponse(body: Images::getLatestPublicImage());
    }
}
