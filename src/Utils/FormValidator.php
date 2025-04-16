<?php

namespace Kuva\Utils;

class FormValidator
{
    private array $fields = [];
    private array $extras_params = [];

    private static function fileValidator(string $name): ?array
    {
        if (!isset($_FILES[$name]) || $_FILES[$name]['error'] != UPLOAD_ERR_OK) {
            return null;
        }

        return $_FILES[$name];
    }
    /**
     * @param array<int,mixed> $mimetypes
     */
    private static function fileValidatorWithMimetypes(string $name, array $mimetypes): ?array
    {
        if (!isset($_FILES[$name]) || $_FILES[$name]['error'] != UPLOAD_ERR_OK) {
            return null;
        }

        var_dump($_FILES[$name]);
        foreach ($mimetypes as $value) {
            if (str_starts_with($_FILES[$name]['type'], $value)) {
                return $_FILES[$name];
            }
        }

        return null;
    }


    private static function textValidator(string $name): ?string
    {
        if (!isset($_POST[$name])) {
            return null;
        }

        return htmlspecialchars($_POST[$name]);
    }


    private static function textValidatorWithMaxLength(string $name, int $length): ?string
    {
        $name = self::textValidator($name);

        if ($name == null || strlen($name) >= $length) {
            return null;
        }

        return $name;
    }

    private static function intValidator(string $name): ?int
    {
        $name = self::textValidator($name);
        return filter_var($name, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
    }

    private static function emailValidator(string $name): ?string
    {
        if (!isset($_POST[$name])) {
            return null;
        }

        return filter_var(htmlspecialchars($_POST[$name]), FILTER_SANITIZE_EMAIL, FILTER_NULL_ON_FAILURE | FILTER_FLAG_EMPTY_STRING_NULL);
    }


    private static function emailValidatorWithMaxLength(string $name, int $length): ?string
    {
        $name = self::emailValidator($name);

        if ($name == null || strlen($name) >= $length) {
            return null;
        }

        return $name;
    }


    private static function checkboxValidator(string $name): bool
    {
        return array_key_exists($name, $_POST);
    }

    /**
     * @param callable(): mixed $validator
     * @param array<int,mixed> $extras
     */
    private function addCustomValidatorField(string $name, callable $validator, array $extras = []): void
    {
        $this->fields[$name] = $validator;
        $this->extras_params[$name] = $extras;
    }

    public function addTextField(string $name): FormValidator
    {
        $this->addCustomValidatorField(
            $name,
            function (string $name) {
                return FormValidator::textValidator($name);
            }
        );

        return $this;
    }

    public function addOptionalTextField(string $name): FormValidator
    {
        $this->addCustomValidatorField(
            $name,
            function (string $name) {
                return FormValidator::textValidator($name) ?? "";
            }
        );

        return $this;
    }


    public function addTextFieldWithMaxLength(string $name, int $length): FormValidator
    {
        $this->addCustomValidatorField(
            $name,
            function (string $name, int $length) {
                return FormValidator::textValidatorWithMaxLength($name, $length);
            },
            [$length]
        );

        return $this;
    }

    public function addEmailField(string $name): FormValidator
    {
        $this->addCustomValidatorField(
            $name,
            function (string $name) {
                return FormValidator::emailValidator($name);
            }
        );

        return $this;
    }


    public function addEmailFieldWithMaxLength(string $name, int $length): FormValidator
    {
        $this->addCustomValidatorField(
            $name,
            function (string $name, int $length) {
                return FormValidator::emailValidatorWithMaxLength($name, $length);
            },
            [$length]
        );

        return $this;
    }

    public function addIntField(string $name): FormValidator
    {
        $this->addCustomValidatorField(
            $name,
            function (string $name) {
                return FormValidator::intValidator($name);
            },
        );

        return $this;
    }

    public function addFileField(string $name): FormValidator
    {
        $this->addCustomValidatorField(
            $name,
            function (string $name) {
                return FormValidator::fileValidator($name);
            }
        );

        return $this;
    }
    /**
     * @param array<int,string> $mimes
     */
    public function addFileFieldWithAcceptedMimeType(string $name, array $mimes): FormValidator
    {
        $this->addCustomValidatorField(
            $name,
            function (string $name, $mimes) {
                return FormValidator::fileValidatorWithMimetypes($name, $mimes);
            },
            [$mimes]
        );

        return $this;
    }

    public function addCheckBoxField(string $name): FormValidator
    {
        $this->addCustomValidatorField(
            $name,
            function (string $name) {
                return FormValidator::checkboxValidator($name);
            }
        );

        return $this;
    }

    public function validate(): bool|array
    {
        $array = [];
        foreach ($this->fields as $key => $validator) {
            $extras = $this->extras_params[$key];
            $value = $validator($key, ...$extras);
            if ($value === null) {
                echo "{$key} is unvalid";
                return false;
            }
            $array[$key] = $value;
        }

        return $array;
    }
}
