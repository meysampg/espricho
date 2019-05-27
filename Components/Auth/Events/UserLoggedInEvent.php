<?php

namespace Espricho\Components\Auth\Events;

use Espricho\Components\Contracts\Authenticatable;

/**
 * Class UserLoggedInEvent
 *
 * @package Espricho\Components\Auth\Events
 */
class UserLoggedInEvent
{
    protected $user;

    /**
     * UserLoggedInEvent constructor.
     *
     * @param Authenticatable $user
     */
    public function __construct(Authenticatable $user)
    {
        $this->user = $user;
    }

    /**
     * @return Authenticatable
     */
    public function getUser(): Authenticatable
    {
        return $this->user;
    }

    /**
     * @param Authenticatable $user
     */
    public function setUser(Authenticatable $user)
    {
        $this->user = $user;
    }
}
