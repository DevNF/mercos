<?php

namespace Fuganholi\MercosIntegration\Helpers;

use Fuganholi\MercosIntegration\Dto\HttpParam;
use Fuganholi\MercosIntegration\Dto\HttpParamCollection;

class Formatter
{
    private static array $upperChar = [
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
        'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
    ];

    private static array $upperCharRegex = [
        "/A/", "/B/", "/C/", "/D/", "/E/", "/F/", "/G/", "/H/", "/I/", "/J/", "/K/", "/L/", "/M/",
        "/N/", "/O/", "/P/", "/Q/", "/R/", "/S/", "/T/", "/U/", "/V/", "/W/", "/X/", "/Y/", "/Z/"
    ];

    private static array $snakeChar = [
        '_a', '_b', '_c', '_d', '_e', '_f', '_g', '_h', '_i', '_j', '_k', '_l', '_m',
        '_n', '_o', '_p', '_q', '_r', '_s', '_t', '_u', '_v', '_w', '_x', '_y', '_z'
    ];

    private static array $snakeCharRegex = [
        "/_a/", "/_b/", "/_c/", "/_d/", "/_e/", "/_f/", "/_g/", "/_h/", "/_i/", "/_j/", "/_k/", "/_l/", "/_m/",
        "/_n/", "/_o/", "/_p/", "/_q/", "/_r/", "/_s/", "/_t/", "/_u/", "/_v/", "/_w/", "/_x/", "/_y/", "/_z/"
    ];

    public static function formatHttpParams(HttpParamCollection $params): string
    {
        $processedParams = [];

        /**
         * @var HttpParam $param
         */
        foreach ($params->all() as $param) {
            $processedParams[] = urlencode($param->name) . '=' . urlencode($param->value);
        }

        return implode('&', $processedParams);
    }

    public static function formatDateTime(string $date, string $timezone = 'America/Sao_Paulo'): \DateTime
    {
        return new \DateTime($date, new \DateTimeZone($timezone));
    }

    public static function convertToFormData(array $data): array
    {
        $convertedData = [];
        $recursive = true;

        foreach ($data as $key => $value) {
            if (!is_array($value)) {
                $convertedData[$key] = $value;
                continue;
            }

            foreach ($value as $subKey => $subValue) {
                $convertedData["$key[$subKey]"] = $subValue;

                if (is_array($subValue)) $recursive = true;
            }
        }

        return $recursive ? self::convertToFormData($convertedData) : $convertedData;
    }

    public static function snake(string $text): string
    {
        return preg_replace(self::$upperCharRegex, self::$snakeChar, $text);
    }

    public static function fromSnakeToCamel(string $text): string
    {
        return preg_replace(self::$snakeCharRegex, self::$upperChar, $text);
    }
}