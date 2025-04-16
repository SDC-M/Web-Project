<?php

namespace Kuva\Handler\Feed;

use Kuva\Backend\Images;
use Kuva\Backend\Middleware\UserMiddleware;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\JsonResponse;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class Get extends Handler
{
    public function handle(Request $req): void
    {
        $user = UserMiddleware::getFromSession();

        if (!isset($req->uri_queries["beforeId"])) {
            $this->response = new JsonResponse(body: Images::getLatestPublicImage());
            return;
        }


        $r = $req->uri_queries["beforeId"];
        $q = filter_var($r, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        if ($q == null) {
            $this->response = new Response(400, "Bad request: beforeId is not valid");
            return;
        }

        $this->response = new JsonResponse(body: Images::getLatestPublicImageBefore($q));
        return;


    }
}
