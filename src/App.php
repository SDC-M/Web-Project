<?php

namespace Kuva;

error_reporting(E_ALL);
ini_set('display_errors', true);

use Kuva\Utils\Router;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class Ee extends Handler
{
    public function handle(Request $req): Response
    {
        echo 'Salut '.$req->extracts['user'].' !';
        return new Response(200);
    }
}

class Aa extends Handler
{
    public function handle(Request $req): Response
    {
        return new Response(200, 'aaaa');
    }
}

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
