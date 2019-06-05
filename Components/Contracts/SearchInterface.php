<?php

namespace Espricho\Components\Contracts;

/**
 * Interface SearchInterface define rules of a search engine rules
 *
 * @package Espricho\Components\Contracts
 */
interface SearchInterface
{
    /**
     * Return an array as results of looking for a Searchable entity
     *
     * @param SearchConditionInterface $searchable
     *
     * @return array
     */
    public function searchFor(SearchConditionInterface $searchable): array;

    /**
     * Update an index for new version of a Searchable entity
     *
     * @param SearchableInterface $searchable
     *
     * @return bool
     */
    public function updateIndexFor(SearchableInterface $searchable): bool;

    /**
     * Create an index for a Searchable entity
     *
     * @param SearchableInterface $searchable
     *
     * @return bool
     */
    public function createIndexFor(SearchableInterface $searchable): bool;

    /**
     * Delete an index of a Searchable entity
     *
     * @param SearchableInterface $searchable
     *
     * @return bool
     */
    public function deleteIndexFor(SearchableInterface $searchable): bool;
}
