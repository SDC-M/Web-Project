<?php

namespace Kuva\Utils\Router;

class Path
{
    public function __construct(public string $path) {}

    public function isEqual(string $path): bool
    {
        return $this->path == $path;
    }

    private function isExtractableParts(string $part): bool {
        return str_starts_with($part, '{') && str_ends_with($part, '}');
    }

    public function resolve(string $uri): bool
    {

        $r = explode('/', $this->path);
        $uri = explode('/', $uri);

        if (count($r) != count($uri)) {
            return false;
        }
        
        foreach ($r as $i => $part) {
            if ($this->isExtractableParts($part)) {
                continue;
            }
            
            if ($uri[$i] != $part) {
                return false;
            }
        }

        return true;
    }

    public function extract(string $uri): array {
        $ex = Extractor::fromPath($this->path);
        return $ex->extract($uri);
    }
}

class Extractor {   
    public function __construct(private array $path_parts)
    {
    }

    public static function fromPathParts(array $path_parts): static {
        return new static($path_parts);
    }    

    public static function fromPath(string $path): static {
        return new static(explode('/', $path));
    }

    private static function getNameOfPart(string $part): string {
        return substr($part, 1, strlen($part)-2);
    }

    public function extract(string $uri): array {
        $uri_parts = explode('/', $uri);
        $extract = [];
        foreach ($this->path_parts as $i => $part) {
            if (str_starts_with($part, '{') && str_ends_with($part, '}')) {
                $name = self::getNameOfPart($part);
                $extract[$name] = $uri_parts[$i];
            }
        }

        return $extract;
    }
}
