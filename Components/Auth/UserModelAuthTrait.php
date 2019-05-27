<?php

namespace Espricho\Components\Auth;

/**
 * Trait UserModelAuthTrait provides some common JWT functionality for user model
 *
 * @package Espricho\Components\Auth
 */
trait UserModelAuthTrait
{
    /**
     * Defines claims which must be used to generating/validating a token
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [
             'hashid'   => $this->id,
             'username' => $this->username,
        ];
    }
}
