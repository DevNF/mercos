<?php

namespace Fuganholi\MercosIntegration\Dto;

use DateTime;
use Fuganholi\MercosIntegration\Helpers\Formatter;

class Serializable
{
    protected array $hidden = [];

    protected static array $casts = [];

    public static function create(\stdClass $object): static
    {
        $instance = new static();

        foreach ($object as $attribute => $value) {
            if (is_array($value) || is_object($value)) continue;

            $key = Formatter::snake($attribute);

            if (!property_exists($instance, $key)) continue;

            if (isset(static::$casts[$key])) {
                $instance->$key = self::parseCastToAttribute($key, $value);
                continue;
            }

            $instance->$key = $value;
        }

        return $instance;
    }

    private static function parseCastToAttribute(string $attribute, mixed $value): mixed
    {
        $rules = explode(':', static::$casts[$attribute]);

        if (empty($rules)) return $value;

        switch ($rules[0]) {
            case 'integer':
                return (int) $value;
            case 'decimal':
            case 'float':
                return (float) $value;
            case 'date':
                return Formatter::formatDateTime($value);
            default:
                return $value;
        }
    }

    public static function createFromResponse(HttpResponse $response): static
    {
        $customer = $response->getResponseJson();

        return static::create($customer);
    }

    /**
     * @return static[]
     */
    public static function createAllFromResponse(HttpResponse $response)
    {
        $customers = [];

        foreach ($response->getResponseJson() as $customer) {
            $customers[] = static::create($customer);
        }

        return $customers;
    }

    public function toArray(): array
    {
        $attributes = get_object_vars($this);

        return $this->convertToArray($attributes);
    }

    public function convertToArray(array $attributes): array
    {
        $serialized = [];

        foreach ($attributes as $attribute => $value) {
            if (in_array($attribute, [...$this->hidden, 'hidden', 'casts'])) continue;

            $key = Formatter::snake($attribute);

            if ($value instanceof Serializable) {
                $serialized[$key] = $value->toArray();
                continue;
            }

            if ($value instanceof DateTime) {
                $format = static::$casts[$attribute] ?? 'Y-m-d H:i:s';

                $format = preg_replace("/date:/", '', $format);

                $serialized[$key] = $value->format($format);
                continue;
            }

            if (is_array($value)) {
                $serialized[$key] = $this->convertToArray($value);
                continue;
            }

            if (!is_null($value)) $serialized[$key] = $value;
        }

        return $serialized;
    }
}