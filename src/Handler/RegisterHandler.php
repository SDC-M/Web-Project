<?php

namespace Kuva\Handler;

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

        $form_value = (new FormValidator())
                        ->addTextField("username")
                        ->addTextField("email")
                        ->addTextField("password")
                        ->addTextField("recovery_answer")
                        ->validate();

        if ($form_value === false) {
            return;
        }

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

        $this->response = new Response(301, headers: ['Location' => '/']);
    }
}
