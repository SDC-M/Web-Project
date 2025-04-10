<?php

namespace Kuva\Handler\Logs;

use Kuva\Backend\Logs;
use Kuva\Backend\Middleware\UserMiddleware;
use Kuva\Backend\User;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\JsonResponse;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class GetLogs extends Handler
{
    public function handle(Request $req): void
    {
        $user = UserMiddleware::getFromSession();

        /* Hackish work-around while there is no permission */
        if ($user->id != 1) {
            return;
        }

        // Executed_by => executed_by = id

        if (isset($req->uri_queries['before'])) {
            $before_id = $req->uri_queries["before"];

            if (isset($req->uri_queries["filter"])) {
                $this->response = new Response(400, "Bad request");

                if ($req->uri_queries['filter'] != 'executed_by') {
                    return;
                }

                $user = User::getByName($req->uri_queries['executor']);
                if ($user == null) {
                    $this->response = new Response(404, "User doesn't exist");
                    return;
                }

                $logs = Logs::getLogsByExecutorBefore($req->uri_queries['before'], $user);
                $this->response = new JsonResponse(200, $logs);
                return;
            }

            $logs = Logs::getLogsBefore($before_id);
            $this->response = new JsonResponse(200, $logs);
            return;
        };


        if (isset($req->uri_queries["filter"])) {
            $this->response = new Response(400, "Bad request");
            if ($req->uri_queries['filter'] != 'executed_by') {
                return;
            }

            $user = User::getByName($req->uri_queries['executor']);
            if ($user == null) {
                $this->response = new Response(404, "User doesn't exist");
                return;
            }
            $logs = Logs::getFirstsLogsByExecutor($user);
            $this->response = new JsonResponse(200, $logs);
            return;
        }

        $logs = Logs::getFirstsLogs();
        $this->response = new JsonResponse(200, $logs);
    }
}
