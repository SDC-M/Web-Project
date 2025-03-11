<?php

namespace Kuva\Handler;

use Kuva\Backend\User;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;
use Kuva\Utils\SessionVariable;

class RecoveryHandler extends Handler
{
    public function handle(Request $req): void
    {
        if (! isset($_POST['username']) || ! isset($_POST['password'])) {
            echo 'A field is not set';
            $this->response = new Response(400);

            return;
        }
        // TODO: Verify input
        $login = User::getByNameAndRecoverykey($_POST['username'], $_POST['recovery_answer']);
        echo ('ici');
        if ($login == null) {
            echo ('bbb');
            $this->response = new Response(400);
            return;
        }

        if ($login->recovery != $_POST['recovery_answer']) {
            echo ('aaa');
            $this->response = new Response(400);
            return;
        }
        $login->updatePassword($_POST['password']);
    }
}
