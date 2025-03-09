<?php

namespace Kuva;

error_reporting(E_ALL);
ini_set('display_errors', true);

use Kuva\Handler\LoginHandler;
use Kuva\Utils\Router;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;
use Kuva\Handler\RegisterHandler;

class App
{
    public function __construct()
    {
        $r = new Router;
        $r->get('/', new Aa)
            ->post('/login', new LoginHandler)
            ->post('/register', new RegisterHandler)
            ->handleCurrent();
    }
}

class Ee extends Handler
{
    public function handle(Request $req): void
    {
        echo 'Salut '.$req->extracts['user'].' !';

        $this->response = new Response(200);
    }
}

class Aa extends Handler
{
    public bool $is_bufferize = false;

    public function handle(Request $req): void
    {
        $this->response = new Response(200, 'aaaa');
    }
}
