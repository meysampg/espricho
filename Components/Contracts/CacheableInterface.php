<?php

namespace Espricho\Components\Contracts;

/**
 * Interface CacheableInterface defines a cacheable entity rules
 *
 * @package Espricho\Components\Contracts
 */
interface CacheableInterface
{
    /**
     * Define a cache key
     *
     * @return string
     */
    public function cacheKey(): string;
}
