<?php

namespace Kuva\Handler\Logs;

use Kuva\Backend\Logs;
use Kuva\Backend\Middleware\UserMiddleware;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\JsonResponse;
use Kuva\Utils\Router\Request;

class GetLogs extends Handler
{
    public function handle(Request $req): void
    {
        $user = UserMiddleware::getFromSession();

        /* Hackish work-around while there is no permission */
        if ($user->id != 1) {
            return;
        }

        if (isset($req->uri_queries["after"])) {
            $after_id = $req->uri_queries["after"];

            $logs = Logs::getLogsAfter($after_id);
            $this->response = new JsonResponse(200, $logs);
            return;
        };

        $logs = Logs::getFirstsLogs();
        $this->response = new JsonResponse(200, $logs);
    }
}
