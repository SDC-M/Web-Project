<?php

namespace Kuva\Utils;

class FormValidator
{
    private array $fields = [];

    public static function fileValidator(string $name): ?array
    {
        if (!isset($_FILES[$name])) {
            return null;
        }

        return $_FILES[$name];
    }


    public static function textValidator(string $name): ?string
    {
        if (!isset($_POST[$name])) {
            return null;
        }

        return $_POST[$name];
    }


    public static function checkboxValidator(string $name): bool
    {
        return array_key_exists($name, $_POST);
    }

    /**
     * @param callable(): mixed $validator
     */
    public function addCustomValidatorField(string $name, callable $validator): void
    {
        $this->fields[$name] = $validator;
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
            $value = $validator($key);
            if ($value === null) {
                return false;
            }
            $array[$key] = $value;
        }

        return $array;
    }
}
