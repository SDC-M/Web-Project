<?php

namespace Kuva\Utils\Router;

abstract class Handler
{
    public ?Response $response = null;

    public function handleAndResponse(Request $req): Response
    {
        
        ob_start();
        $this->handle($req);        
        $b = ob_get_clean();

        if ($this->response == null) {
            $this->response = new Response(200, "");
        } 

        if ($b != null) {
            $this->response->body .= $b;
            $this->response->headers["Content-Type"] = "text/html";
        }

        return $this->response;
    }

    abstract public function handle(Request $req): void;
}
