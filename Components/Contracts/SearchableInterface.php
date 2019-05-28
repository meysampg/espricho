<?php

namespace Espricho\Components\Contracts;

/**
 * Interface SearchableInterface provides a searchable entity rules
 *
 * @package Espricho\Components\Contracts
 */
interface SearchableInterface
{
    /**
     * Return a key which the search engine must use it to search
     *
     * @return string
     */
    public function searchKey(): string;

    /**
     * Return the index which a search engine must search on it
     *
     * @return string
     */
    public function searchIndex(): string;
}
