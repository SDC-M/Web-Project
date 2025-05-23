<?php

namespace Kuva\Handler\User;

use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;
use Kuva\Utils\SessionVariable;

class DisconnectHandler extends Handler
{
    public function handle(Request $req): void
    {
        SessionVariable::destroy();
        $this->response = new Response(302, "You have been disconnected", ["Location" => "/"]);
    }
}
