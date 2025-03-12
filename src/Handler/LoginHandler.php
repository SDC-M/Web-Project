<?php

namespace Kuva\Handler;

use Kuva\Backend\User;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;
use Kuva\Utils\SessionVariable;

class LoginHandler extends Handler
{
    public function handle(Request $req): void
    {
        if (! isset($_POST['username']) || ! isset($_POST['password'])) {
            echo 'A field is not set';
            $this->response = new Response(400);

            return;
        }
        // TODO: Verify input
        $login = User::getByNameAndPassword($_POST['username'], $_POST['password']);
        if ($login == null) {
            $this->response = new Response(400);

            return;
        }
        (new SessionVariable())->setUserId($login->id);
        $this->response = new Response(301, headers: ['Location' => '/profile']);
    }
}
