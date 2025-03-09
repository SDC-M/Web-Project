<?php

namespace Kuva\Utils\Router;

interface Handler
{
    public function handle(Request $req): Response;
}
