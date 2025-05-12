<?php

namespace Fuganholi\MercosIntegration\Traits;

use Fuganholi\MercosIntegration\Dto\GetParams;
use Fuganholi\MercosIntegration\Dto\User\Usuario;
use Fuganholi\MercosIntegration\Exceptions\GetUsersException;
use Fuganholi\MercosIntegration\Helpers\Thrower;

trait UserEntity
{
    use HttpClientMethods;

    const USER_ENTITY = '/v1/usuarios';

    /**
     * @return Usuario[]
     */
    public function getUsers(GetParams $params = new GetParams())
    {
        $response = $this->get(self::USER_ENTITY, $params->all());

        if (!$response->isSuccess()) {
            Thrower::withHttpResponse($response)
                ->throwException(GetUsersException::class, 'An error occured when trying to get all users!');
        }

        return Usuario::createAllFromResponse($response);
    }

    public function getUser(int $userId): Usuario
    {
        $response = $this->get(self::USER_ENTITY . "/$userId");

        if (!$response->isSuccess()) {
            Thrower::withHttpResponse($response)
                ->throwException(GetUsersException::class, 'An error occured when trying to get the user!');
        }

        return Usuario::createFromResponse($response);
    }
}