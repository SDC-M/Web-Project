<?php

namespace Kuva;

error_reporting(E_ALL);
ini_set('display_errors', true);

use Kuva\Utils\Router;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class App
{
    public function __construct()
    {
        $r = new Router;
        $r->get('/', new Aa)
            ->get('/{user}/eee', new Ee)
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
