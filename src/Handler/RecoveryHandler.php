<?php

namespace Kuva\Handler;

use Kuva\Backend\User;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class RecoveryHandler extends Handler
{
    public function handle(Request $req): void
    {
        if (! isset($_POST['username']) || ! isset($_POST['password']) || ! isset($_POST["recovery_answer"])) {
            echo 'A field is not set';
            $this->response = new Response(400);

            return;
        }
        // TODO: Verify input
        $login = User::getByNameAndRecoverykey($_POST['username'], $_POST['recovery_answer']);
        if ($login == null) {
            $this->response = new Response(400, headers: ['Location' => '/login']);
            return;
        }

        if ($login->recovery != $_POST['recovery_answer']) {
            $this->response = new Response(400, headers: ['Location' => '/login']);
            return;
        }
        $login->updatePassword($_POST['password']);
    }
}
