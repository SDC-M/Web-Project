<?php

namespace Kuva\Handler\User;

use Kuva\Backend\Logs;
use Kuva\Backend\Middleware\FormMiddleware;
use Kuva\Backend\User;
use Kuva\Utils\FormValidator;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class RegisterHandler extends Handler
{
    public function handle(Request $req): void
    {


        $this->response = new Response(400);

        $form_value = FormMiddleware::validate((new FormValidator())
                        ->addTextFieldWithMaxLength("username", 100)
                        ->addEmailFieldWithMaxLength("email", 100)
                        ->addTextField("password")
                        ->addTextField("recovery_answer"));

        // TODO: Verify input
        $registered = User::register(
            $form_value['username'],
            $form_value['email'],
            $form_value['password'],
            $form_value['recovery_answer']
        );

        if (! $registered) {
            $this->response = new Response(500);
            return;
        }

        Logs::create_with("New user have been created with username {$form_value['username']} change his password");

        $this->response = new Response(301, headers: ['Location' => '/']);
    }
}
