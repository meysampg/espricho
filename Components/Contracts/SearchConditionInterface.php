<?php

namespace Espricho\Components\Contracts;

/**
 * Interface SearchConditionInterface define rules which can be used to define a
 * rule set for searching
 *
 * @package Espricho\Components\Contracts
 */
interface SearchConditionInterface
{
    /**
     * The index of searchable interface
     *
     * @return string
     */
    public function getIndex(): string;

    /**
     * Return an array of elastic search query conditions
     *
     * @return array
     */
    public function build(): array;
}
