<?php

namespace Espricho\Components\Contracts;

/**
 * Interface ConfigManagerInterface defines the rules of a config manager
 *
 * @package Espricho\Components\Contracts
 */
interface ConfigManagerInterface
{
    /**
     * It get a dot-notation string as the key and it as a configuration
     *
     * @param string $key
     * @param mixed  $value
     */
    public function addRaw(string $key, $value): void;
}
