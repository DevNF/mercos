<?php

namespace Fuganholi\MercosIntegration\Facades\Validation;

use DateTime;

class Validator
{
    private static ValidationErrorMessages $errors;

    public function __call($name, $arguments)
    {
        if (method_exists($this, $name)) {
            self::$name(...$arguments);
        }
    }

    public static function __callStatic($name, $arguments)
    {
        if (method_exists(self::class, $name)) {
            self::$name(...$arguments);
        }
    }

    public static function fails(): bool
    {
        return self::$errors->hasErrors();
    }

    public static function errors(): ValidationErrorMessages
    {
        return self::$errors;
    }

    public static function validate(array $fieldsValues, array $fieldsRules): self
    {
        self::$errors = new ValidationErrorMessages();

        foreach ($fieldsRules as $field => $rules) {
            if (stripos($rules, 'nullable') !== false && (!isset($fieldsValues[$field]) || is_null($fieldsValues[$field]))) continue;

            $rules = explode('|', $rules);
            $path = explode('.', $field);

            $errors = self::check($fieldsValues, $rules, $path);

            if (!empty($errors)) self::$errors[$field] = $errors;
        }

        return new self();
    }

    private static function check(array $data, array $rules, array $path, mixed $index = '')
    {
        $part = array_shift($path);

        if ($part === '*') {
            foreach ($data as $i => $item) {
                self::check($item, $rules, $path, self::fullPath($index, $i));
            }

            return;
        }

        if (!empty($path) && isset($data[$part])) {
            self::check($data[$part], $rules, $path, self::fullPath($index, $part));
            return;
        }

        $field = self::fullPath($index, $part);
        $value = $data[$part] ?? null;

        if (in_array('nullable', $rules) && is_null($value)) return;

        if (in_array('required', $rules) && is_null($value)) {
            self::$errors->setError($field, "O campo '$field' é obrigatório");

            return;
        }

        foreach ($rules as $rule) {
            $explode = explode(':', $rule);

            $rule = $explode[0];

            $attributes = (isset($explode[1]) && !empty($explode[1])) ? explode(',', $explode[1]) : [];

            switch ($rule) {
                case 'min':
                    $min = $attributes[0] ?? null;
                    if (!is_null($value) && is_numeric($min) && !self::min($value, $min)) {
                        $msg = "O campo '$field' deve conter ";
                        if (is_numeric($value)) $msg .= "um valor mínimo de $min";
                        else if (is_string($value)) $msg .= "no mínimo $min caracteres";
                        else $msg .= "no mínimo $min posições";

                        self::$errors->setError($field, $msg);
                    }
                    break;
                case 'max':
                    $max = $attributes[0] ?? null;
                    if (!is_null($value) && is_numeric($max) && !self::max($value, $max)) {
                        $msg = "O campo '$field' deve conter ";
                        if (is_numeric($value)) $msg .= "um valor máximo de $max";
                        else if (is_string($value)) $msg .= "no máximo $max caracteres";
                        else $msg .= "no máximo $max posições";

                        self::$errors->setError($field, $msg);
                    }
                    break;
                case 'between':
                    $min = $attributes[0] ?? null;
                    $max = $attributes[1] ?? null;
                    if (!is_null($value) && is_numeric($min) && is_numeric($max) && !self::between($value, $min, $max)) {
                        $msg = "O campo '$field' deve conter ";
                        if (is_numeric($value)) $msg .= "um valor entre $min e $max";
                        else if (is_string($value)) $msg .= "entre $min e $max caracteres";
                        else $msg .= "entre $min e $max posições";

                        self::$errors->setError($field, $msg);
                    }
                    break;
                case 'in':
                    if (!empty($attributes) && !in_array($value, $attributes)) {
                        $attributesString = implode(', ', $attributes);
                        self::$errors->setError(
                            $field,
                            "O campo '$field' deve conter um dos seguintes valores: $attributesString"
                        );
                    }
                    break;
                case 'date_format':
                    $format = $attributes[0] ?? null;
                    if (is_string($value) && !empty($format) && !self::dateFormat($value, $format)) {
                        self::$errors->setError(
                            $field,
                            "O campo '$field' deve conter um dava válida no formato $format"
                        );
                    }
                default:
                break;
            }
        }
    }

    private static function fullPath($index, $part): string
    {
        if ($index === '' || $index === '*') return $part;

        return "$index.$part";
    }

    private static function min(mixed $value, int|float $min): bool
    {
        return (
            (is_numeric($value) && $value >= $min) ||
            (is_string($value) && strlen($value) >= $min) ||
            (is_array($value) && count($value) >= $min)
        );
    }

    private static function max(mixed $value, int|float $max): bool
    {
        return (
            (is_numeric($value) && $value <= $max) ||
            (is_string($value) && strlen($value) <= $max) ||
            (is_array($value) && count($value) <= $max)
        );
    }

    private static function between(mixed $value, int|float $min, int|float $max): bool
    {
        return self::min($value, $min) && self::max($value, $max);
    }

    private static function dateFormat(string $value, string $format): bool
    {
        $date = DateTime::createFromFormat($format, $value);

        if (!$date) return false;

        return $value === $date->format($format);
    }
}