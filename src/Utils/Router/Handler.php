<?php

namespace Kuva\Utils\Router;

abstract class Handler
{
    public bool $is_bufferize = true;

    abstract public function handle(Request $req): Response;
}
