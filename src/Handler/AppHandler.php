<?php

namespace Kuva\Handler;

use Kuva\Backend\User;
use Kuva\Utils\FileConstant;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class AppHandler extends Handler {
    public bool $is_bufferize = false;
    
    private function showLoginPage(): void {
        $this->response = new Response(200, file_get_contents(FileConstant::LOGINPAGE));
    }
    
    public function handle(Request $req): void
    {
        session_start();
        $r = User::getFromSession();
        if ($r == null) {
            $this->showLoginPage();
            return;
        }
        
        $this->response = new Response(200, "Connected");
    }
}
