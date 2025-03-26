<?php

namespace Kuva\Handler;

use Kuva\Backend\User;
use Kuva\Utils\FileConstant;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class AppHandler extends Handler
{
    private function showUnconnectedPage(): void
    {
        $this->response = new Response(200, file_get_contents(FileConstant::HOMEPAGE));
    }

    private function showConnectedPage(): void
    {
        $this->response = new Response(200, file_get_contents(FileConstant::FEED));
    }

    public function handle(Request $req): void
    {
        session_start();
        $r = User::getFromSession();
        if ($r == null) {
            $this->showUnconnectedPage();
            return;
        }

        $this->showConnectedPage();
    }
}
