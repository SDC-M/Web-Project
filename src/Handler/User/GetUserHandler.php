<?php

namespace Kuva\Handler\User;

use Kuva\Backend\User;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class GetUserHandler extends Handler
{
    public bool $is_bufferize = false;
    public function getCurrentUser(): void
    {
        $user = User::getFromSession();
        if ($user == null) {
            return;
        }
        $this->response = new Response(200, $user->jsonify(), ['Content-Type' => 'application/json']);
    }

    public function handle(Request $req): void
    {
        $this->response = new Response(200);
        $id = $req->extracts["id"];
        if ($id == null) {
            return;
        }

        if ($id == 'me') {
            $this->getCurrentUser();
            return;
        }


    }
}
