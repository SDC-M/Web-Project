<?php

namespace Kuva\Handler\User;

use Kuva\Backend\Logs;
use Kuva\Backend\Middleware\FormMiddleware;
use Kuva\Backend\User;
use Kuva\Utils\FormValidator;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;
use Kuva\Utils\SessionVariable;

class LoginHandler extends Handler
{
    public function handle(Request $req): void
    {

        $form_values = FormMiddleware::validate((new FormValidator())
                            ->addTextField("username")
                            ->addTextField("password"));

        $login = User::getByNameAndPassword($form_values['username'], $form_values['password']);
        if ($login == null) {
            $this->response = new Response(400, headers: ['Location' => '/login']);
            return;
        }
        (new SessionVariable())->setUserId($login->id);

        Logs::create_with("User {$login->id} is logged", $login);
        
        $this->response = new Response(301, headers: ['Location' => '/profile']);
    }
}
