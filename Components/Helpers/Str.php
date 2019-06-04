<?php

namespace Espricho\Components\Helpers;

use function str_replace;
use function strtolower;
use function trim;
use function ucfirst;

/**
 * Class Str provides static helpers to work with strings
 *
 * @package Espricho\Components\Helpers
 */
class Str
{
    /**
     * Convert a snake_case work to Snake case word
     *
     * @param string $str
     *
     * @return string
     */
    public static function snakeToTitle(string $str): string
    {
        return str_replace('_', ' ', ucfirst(strtolower($str)));
    }

    /**
     * Split a string into array with a given delimiter
     *
     * @param string $str
     * @param string $del
     *
     * @return array
     */
    public static function split(string $str, string $del = ','): array
    {
        $str = trim($str, $del);
        if (!$str) {
            return [];
        }

        return explode($del, $str);
    }
}
