<?php

namespace Espricho\Components\Helpers;

use function implode;
use function array_slice;

/**
 * Class Arr provides some static methods to working with array
 *
 * @package Espricho\Components\Helpers
 */
class Arr
{
    /**
     * Convert an array to string with a given delimiter
     *
     * @param array  $arr
     * @param string $del
     *
     * @return string
     */
    public static function Implode(array $arr, string $del): string
    {
        return implode($del, $arr);
    }

    /**
     * Implode a slice of a given array
     *
     * @param array  $arr
     * @param string $del
     * @param int    $from
     * @param int    $to
     *
     * @return string
     */
    public static function ImplodeByPosition(
         array $arr,
         string $del,
         int $from,
         int $to = -1
    ): string {
        if ($to === -1) {
            $to = count($arr);
        }

        return static::Implode(
             array_slice($arr, $from, $to - $from + 1),
             $del
        );
    }
}
