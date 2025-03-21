<?php

namespace Kuva\Utils;

use Kuva\Backend\User;
use Kuva\Utils\Router\Request;

abstract class SubValidator
{
    abstract public function validate(Request $req): bool;

    public function describeError(): string
    {
        return "Bad value in request";
    }
}

class UrlParams extends SubValidator
{
    public function __construct(public string $name, public mixed& $value, public int $filter = FILTER_DEFAULT, private string $error = "")
    {
    }

    public function validate(Request $req): bool
    {
        if (!isset($req->extracts[$this->name])) {
            return false;
        }

        if (filter_var($req->extracts[$this->name], $this->filter) != false) {
            return false;
        }

        return true;
    }

    public function describeError(): string
    {
        return "{$this->name} is not an int";
    }
}


enum FieldType
{
    case Text;
    case Int;
    case File;
    case Checkbox;

    public function toString(): string
    {
        return match ($this) {
            FieldType::Text => "Text",
            FieldType::Int => "Int",
            FieldType::File => "File",
            FieldType::Checkbox => "Checkbox",
        };
    }
}

class FormParams extends SubValidator
{
    public function __construct(public string $name, public mixed& $value, public FieldType $type, private string $error = "", private bool $is_optional = false)
    {
        $this->error = "{$this->name} is not an " . $this->type->toString()        ;
    }

    public function getContent(): mixed
    {
        if ($this->type == FieldType::File) {
            return isset($_FILES[$this->name]) ? $_FILES[$this->name] : null;
        }


        return isset($_POST[$this->name]) ? $_POST[$this->name] : null;
    }

    public function verifyContent(): bool
    {
        return match ($this->type) {
            FieldType::Text => true,
            FieldType::Int => filter_var($this->getContent(), FILTER_VALIDATE_INT),
            FieldType::File => true,
        };
    }

    public function validate(Request $req): bool
    {

        if ($this->getContent() == null) {
            if ($this->is_optional) {
                $value = null;
                return true;
            };

            $this->error = "The field {$this->name} doesn't exists";

            return false;
        }

        if ($this->verifyContent() === false) {
            $this->error = "The field {$this->name} is not valid";
            return false;
        }

        $value = $_POST[$this->name];

        return true;
    }

    public function describeError(): string
    {
        return $this->error;
    }
}

enum SessionHelperType
{
    case SessionUser;
    case ImageFromUrl;
    case Anno;
}

class SessionHelper extends SubValidator
{
    public function __construct(public SessionHelperType $type, public mixed &$value)
    {
    }

    public function validate(Request $req): bool
    {

        $user = User::getFromSession();
        if ($user == null) {
            return false;
        }

        return true;
    }
}

/**
Validator will verify income request and return valid data
*/
class Validator
{
    public function __construct(private array $validations = [])
    {

    }

    public function checkIfUserIsConnected(User& $user): self
    {
        $this->validations["connected_user"] = function (Request $r) {
            $user = User::getFromSession();
            return true;
        };

        return $this;
    }

    public function getIntFromUrlParam(string $name, int& $value): self
    {
        $this->validations["url_params_{$name}"] = new UrlParams($name, $value, FILTER_VALIDATE_INT);
        return $this;
    }


    public function getStringFromUrlParam(string $name, string& $value): self
    {
        $this->validations["url_params_{$name}"] = new UrlParams($name, $value, FILTER_DEFAULT);
        return $this;
    }

    public function getStringFromFormParam(string $name, string& $value): self
    {
        $this->validations["forms_{$name}"] = new FormParams($name, $value, FieldType::Text);
        return $this;
    }

    public function getOptionalStringFromFormParam(string $name, string& $value): self
    {
        $this->validations["forms_{$name}"] = new FormParams($name, $value, FieldType::Text, is_optional: true);
        return $this;
    }

    public function getFileFromFormParam(string $name, string& $value): self
    {
        $this->validations["forms_{$name}"] = new FormParams($name, $value, FieldType::File);
        return $this;
    }

    public function getIntFromFormParam(string $name, string& $value): self
    {
        $this->validations["forms_{$name}"] = new FormParams($name, $value, FieldType::Int);
        return $this;

    }

    public function getCheckboxValueFromFormParam(string $name, string& $value): self
    {
        $this->validations["forms_{$name}"] = new FormParams($name, $value, FieldType::Checkbox);
        return $this;
    }

    public function getUserFromSession(User &$user): self
    {
        $this->validations["get_user_from_session"] = new SessionHelper(UserSession);
        return $this;
    }

    public function validate(Request $req): ?string
    {
        foreach ($this->validations as $key => $validator) {
            if ($validator->validate($req) === false) {
                return $validator->describeError();
            }
        }
        return null;
    }
}
