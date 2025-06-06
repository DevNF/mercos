<?php

namespace Fuganholi\MercosIntegration\Helpers;

use Fuganholi\MercosIntegration\Dto\HttpResponse;
use Fuganholi\MercosIntegration\Facades\Validation\ValidationErrorMessages;
use Throwable;

class Thrower
{
    private static HttpResponse $httpResponse;
    private static ValidationErrorMessages $validationErrorMessages;
    private static int $code = 0;
    private static Throwable|null $previous = null;

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

    public static function throwException(string $exception = \Exception::class, string $message = ''): void
    {
        if (isset(self::$httpResponse)) self::setHttpErrors($message);

        if (
            isset(self::$validationErrorMessages) &&
            self::$validationErrorMessages->hasErrors()
        ) self::setValidationErrorsMessages($message);

        throw new $exception($message, self::$code, self::$previous);
    }

    public static function withHttpResponse(HttpResponse $httpResponse): self
    {
        self::$httpResponse = $httpResponse;

        return new self();
    }

    public static function withValidationErrors(ValidationErrorMessages $validationErrorMessages): self
    {
        self::$validationErrorMessages = $validationErrorMessages;

        return new self();
    }

    public static function setCode(int $code): self
    {
        self::$code = $code;

        return new self();
    }

    public static function setPrevious(Throwable|null $previous): self
    {
        self::$previous = $previous;

        return new self();
    }

    private static function setHttpErrors(string &$message): void
    {
        $body = self::$httpResponse->getResponseJson();

        if (isset($body->mensagem)) {
            $errorMessage = "\r\nError: " . $body->mensagem;

            $message .= $errorMessage;
        }

        if (isset($body->erros) && !empty($body->erros)) {
            $erros = array_map(function ($erro) {
                if (is_object($erro)) {
                    $message = '';

                    if (isset($erro->campo)) $message .= $erro->campo . ': ';

                    $message .= isset($erro->mensagem) ? $erro->mensagem : json_encode($erro);

                    return $message;
                }

                if (is_string($erro)) return $erro;

                return json_encode($erro);
            }, $body->erros);

            $erros = "\r\n" . implode("\r\n", $erros);

            $message .= $erros;
        }

        if (isset($body->limite_de_requisicoes)) {
            $limite = $body->limite_de_requisicoes;
            $espera = $body?->tempo_ate_permitir_novamente ?? 5;

            $message .= "\r\nError: Limite de $limite requisições atingido, aguarde $espera segundos e tente novamente";
        }
    }

    private static function setValidationErrorsMessages(string &$message): void
    {
        $messages = self::$validationErrorMessages->messages();

        foreach ($messages as $field => $errors) {
            $stringError = implode('; ', $errors);

            $message .= "\r\n[$field]: $stringError";
        }
    }
}