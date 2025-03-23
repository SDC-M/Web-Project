<?php

namespace Kuva\Handler;

use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;
use Kuva\Utils\SessionVariable;

class UserIdHandler extends Handler
{
    public function handle(Request $req): void
    {
        $id = (new SessionVariable())->getUserId();
        if ($id === null) {

            $this->response = new Response(400, 'User not connected');
            return;
        }
        $this->response = new Response(body: json_encode(['id' => $id]), headers: ['Content-Type' => 'application/json']);
    }
}
