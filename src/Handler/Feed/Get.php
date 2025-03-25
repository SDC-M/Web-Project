<?php

namespace Kuva\Handler\Feed;

use Kuva\Backend\Images;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;
use Kuva\Utils\SessionVariable;

class Get extends Handler
{
    public function handle(Request $req): void
    {
        $user_id = (new SessionVariable())->getUserId();

        if ($user_id === null) {
            $this->response = new Response(403, "You aren't connected");
            return;
        }

        $this->response = new Response(200, json_encode(Images::getLatestPublicImage()), ["Content-Type" => "application/json"]);
    }
}
