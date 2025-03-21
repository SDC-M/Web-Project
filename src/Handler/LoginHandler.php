<?php

namespace Kuva\Handler;

use Kuva\Backend\User;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;
use Kuva\Utils\SessionVariable;
use Kuva\Utils\Validator;

class LoginHandler extends Handler
{
    public function handle(Request $req): void
    {

        $username = "";
        $password = "";

        $v = (new Validator())
            ->getStringFromFormParam("username", $username)
            ->getStringFromFormParam("password", $password)
            ->validate($req);

        if ($v !== null) {
            $this->response = new Response(400, $v);
            return;
        }

        $login = User::getByNameAndPassword($username, $password);
        if ($login == null) {
            $this->response = new Response(400, headers: ['Location' => '/login']);
            return;
        }
        (new SessionVariable())->setUserId($login->id);
        $this->response = new Response(301, headers: ['Location' => '/profile']);
    }
}
