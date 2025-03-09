<?php

namespace Kuva\Utils\Router;

abstract class Handler
{
    public bool $is_bufferize = true;

    public ?Response $response = null;

    public function handleAndResponse(Request $req): Response
    {
        if ($this->is_bufferize) {
            ob_start();
            $this->handle($req);

            if ($this->response == null) {
                $this->response = new Response(200, '');
            }
            if ($this->response->body != '') {
                trigger_error('The handler ( ' . static::class . ' ) is bufferize but your response have a body so the body will be replace by bufferized output', E_USER_WARNING);
            }
            $b = ob_get_clean();
            $this->response->body = $b;
        } else {
            $this->handle($req);
            if ($this->response == null) {
                return new Response(500, 'Bad implementation in ' . static::class);
            }
        }

        return $this->response;
    }

    abstract public function handle(Request $req): void;
}
