<?php

namespace Kuva\Handler;

use Kuva\Backend\Middleware\FormMiddleware;
use Kuva\Backend\User;
use Kuva\Utils\FormValidator;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class RecoveryHandler extends Handler
{
    public function handle(Request $req): void
    {

        $this->response = new Response(400);

        $form_value = FormMiddleware::validate((new FormValidator())
                        ->addTextField("username")
                        ->addTextField("password")
                        ->addTextField("recovery_answer"));

        
        // TODO: Verify input
        $login = User::getByNameAndRecoverykey($form_value['username'], $form_value['recovery_answer']);

        if ($login == null) {
            $this->response = new Response(400, headers: ['Location' => '/login']);
            return;
        }

        if ($login->recovery != $form_value['recovery_answer']) {
            $this->response = new Response(400, headers: ['Location' => '/login']);
            return;
        }
        $login->updatePassword($form_value['password']);
    }
}
