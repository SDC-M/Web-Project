<?php

namespace Kuva\Utils;

use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Path;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class Router
{
    /**
     * @param  array<string,array<string, Handler>>  $path
     */
    public function __construct(
        public array $path = [],
        public Handler $fallback = new EmptyResponse()
    ) {
    }

    public function handleCurrent(): void
    {
        $req = $this->handleRequest(Request::fromCurrentRequest());
        http_response_code($req->status);
        foreach ($req->headers as $key => $value) {
            header("$key: $value", true);
        }
        echo $req->body;
    }

    private function handleRequest(Request $req): Response
    {
        if (! array_key_exists($req->method, $this->path)) {
            return $this->fallback->handleAndResponse($req);
        }

        foreach ($this->path[$req->method] as $p => $h) {
            $path = new Path($p);
            if ($path->resolve($req->path)) {
                $req->extracts = $path->extract($req->path);

                return $h->handleAndResponse($req);
            }
        }

        return $this->fallback->handleAndResponse($req);
    }

    public function get(string $path, Handler $handler): Router
    {
        $this->path['GET'][$path] = $handler;

        return $this;
    }

    public function post(string $path, Handler $handler): Router
    {
        $this->path['POST'][$path] = $handler;

        return $this;
    }

    public function put(string $path, Handler $handler): Router
    {
        $this->path['PUT'][$path] = $handler;

        return $this;
    }

    public function delete(string $path, Handler $handler): Router
    {
        $this->path['DELETE'][$path] = $handler;

        return $this;
    }

    public function withFallback(Handler $fallback): Router
    {
        $this->fallback = $fallback;

        return $this;
    }
}

class EmptyResponse extends Handler
{
    public function handle(Request $req): void
    {
        $this->response = new Response(404, 'Not found: '.$req->uri);
    }
}
