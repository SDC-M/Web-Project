<?php

namespace Kuva\Utils\Router;

use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

interface Handler {
    public function handle(Request $req): Response;
}
