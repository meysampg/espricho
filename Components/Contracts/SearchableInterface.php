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
     * Return the index which a search engine must search on it. For nested objects
     * return null.
     *
     * @return string
     */
    public function searchIndex(): ?string;

    /**
     * The ID which document must be saved with it as identifier. For nested objects
     * return null.
     *
     * @return string|int|null
     */
    public function searchId();

    /**
     * Provides a mapping of data which must be stored or retrieved from a search
     * engine. Only keys from this return
     *
     * @return array
     */
    public function searchData(): array;
}
