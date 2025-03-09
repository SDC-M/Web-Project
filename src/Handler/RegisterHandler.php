<?php

namespace Kuva\Handler;

use Kuva\Backend\User;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class RegisterHandler extends Handler
{
    public function handle(Request $req): void
    {
        if (! isset($_POST['username']) || ! isset($_POST['email']) || ! isset($_POST['password']) || ! isset($_POST['recovery_answer'])) {
            $this->response = new Response(400);

            return;
        }
        // TODO: Verify input
        $registered = User::register($_POST['username'], $_POST['email'], $_POST['password'], $_POST['recovery_answer']);
        if (!$registered) {
            $this->response = new Response(500);

            return;
        }

        $this->response = new Response(301, headers: ["Location" => "/"]);
    }
}
