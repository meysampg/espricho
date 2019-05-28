<?php

namespace Espricho\Components\Contracts;

/**
 * Interface CacheInterface defines rules of a cache center
 *
 * @package Espricho\Components\Contracts
 */
interface CacheInterface
{
    /**
     * Search on cache for a given key
     *
     * @param string $key
     *
     * @return CacheableInterface
     */
    public function getEntity(string $key): CacheableInterface;

    /**
     * Cache a given entity. If entity already is cached, update it, otherwise
     * create it.
     *
     * @param CacheableInterface $cacheable
     *
     * @return bool
     */
    public function setEntity(CacheableInterface $cacheable): bool;

    /**
     * Delete a cached entity from cache center.
     *
     * @param string $key
     *
     * @return bool
     */
    public function deleteEntity(string $key): bool;
}
