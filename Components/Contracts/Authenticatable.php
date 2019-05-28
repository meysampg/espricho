<?php

namespace Espricho\Components\Contracts;

interface Authenticatable
{
    public const EVENT_LOGGED_IN = 'user_logged_in_event';

    public function getId(): ?int;
}
