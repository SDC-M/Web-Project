<?php

namespace Kuva\Utils\Router;

class Path
{
    /**
        When the path extract a long suffix
        Path: /aaa/{long:+}
        /aaa/eee => long = eee
        /aaa/eeee/aaa/eee => long = eeee/aaa/eee
     */
    private bool $long_suffix;

    private array $path_part;

    public function __construct(public string $path)
    {
        $r = explode('/', $this->path);
        $this->long_suffix = self::isLongMode($r);
    }
    /**
     * @param array<int,mixed> $r
     */
    private static function isLongMode(array $r): bool
    {
        foreach ($r as $path) {
            $matches = [];
            preg_match('(\{([a-zA-Z]+)\:\+})', $path, $matches);
            if (count($matches) != 0) {
                return true;
            }
        }

        return false;
    }

    public function isEqual(string $path): bool
    {
        return $this->path == $path;
    }

    private function isExtractableParts(string $part): bool
    {
        return str_starts_with($part, '{') && str_ends_with($part, '}');
    }

    public function resolve(string $uri): bool
    {
        $r = explode('/', $this->path);
        $uri = explode('/', $uri);

        if (! $this->long_suffix && (count($r) != count($uri))) {
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

    public function extract(string $uri): array
    {
        return (new Extractor(UrlArgs::fromPath($this->path)))->extract($uri);
    }
}

enum ArgsType
{
    case Constant;
    case Variable;
    case LongVariable;
}

class UrlArg
{
    public function __construct(public string $name, public string $value, public ArgsType $type)
    {
    }


    private static function getNameOfPart(string $part): string
    {
        return substr($part, 1, strlen($part) - 2);
    }

    public static function fromPart(string $part): static
    {
        if (str_starts_with($part, '{') && str_ends_with($part, '}')) {
            $name = self::getNameOfPart($part);
            if (str_ends_with($name, ":+")) {
                // Remove the :+ of the name
                return new static(substr($name, 0, strlen($name) - 2), '', ArgsType::LongVariable);
            }

            return new static($name, '', ArgsType::Variable);
        }

        return new static('', $part, ArgsType::Constant);
    }
}

class UrlArgs
{
    /**
     * @param array<int,UrlArg> $parts
     */
    public function __construct(public array $parts)
    {
    }

    public static function fromPath(string $uri): static
    {
        $parts = [];
        foreach (explode('/', $uri) as $part) {
            array_push($parts, UrlArg::fromPart($part));
        }
        return new static($parts);
    }
}

class Extractor
{
    public function __construct(private UrlArgs $url_args)
    {
    }

    public function buildRegex(): string
    {
        $regex = '/^';
        $args_regex = [];

        foreach ($this->url_args->parts as $arg) {
            $arg_regex = match ($arg->type) {
                ArgsType::Constant => "({$arg->value})",
                ArgsType::Variable => "(.+)",
                ArgsType::LongVariable => "(.+(\/)?)+"
            };

            array_push($args_regex, $arg_regex);

            if ($arg->type == ArgsType::LongVariable) {
                break;
            }
        }

        $regex .= implode('\/', $args_regex);
        $regex .= '(\/)?$/';
        return $regex;
    }

    public function extract(string $uri): array
    {
        $extract = [];
        $regex = $this->buildRegex();
        $matches = [];
        $r = preg_match($regex, $uri, $matches);
        foreach (array_slice($matches, 1) as $i => $val) {
            $arg = $this->url_args->parts[$i];
            match ($arg->type) {
                ArgsType::Variable,
                ArgsType::LongVariable => $extract[$arg->name] = $val,
                default => null,
            };
        }
        return $extract;
    }
}
