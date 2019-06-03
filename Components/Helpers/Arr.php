<?php

namespace Espricho\Components\Helpers;

use function implode;
use function array_keys;
use function array_slice;
use function array_filter;

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
    public static function implode(array $arr, string $del): string
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
    public static function implodeByPosition(
         array $arr,
         string $del,
         int $from,
         int $to = -1
    ): string {
        if ($to === -1) {
            $to = count($arr);
        }

        return static::implode(
             array_slice($arr, $from, $to - $from + 1),
             $del
        );
    }

    /**
     * Convert a flatten indexed array to a nested associative array. For example
     * it converts `['my', 'name', 'is']` array to the
     * ```
     * [
     *      'my' => [
     *          'name' => [
     *              'is' => null # or any given value as the parameter
     *          ]
     *      ]
     * ]
     * ```
     *
     * @param array      $arr
     * @param null|mixed $value
     * @param int        $from
     *
     * @return array
     */
    public static function deflat(array $arr, $value = null, int $from = 0): array
    {
        if ($from === count($arr) - 1) {
            return [$arr[$from] => $value];
        }

        return [$arr[$from] => static::deflat($arr, $value, $from + 1)];
    }

    /**
     * Check a given array is associative or not
     *
     * @param array $arr
     *
     * @return bool
     */
    public static function isNonAssociative(array $arr): bool
    {
        return count($arr) === count(array_filter(array_keys($arr), 'is_numeric'));
    }

    /**
     * Check a given array is associative or not
     *
     * @param array $arr
     *
     * @return bool
     */
    public static function isAssociative(array $arr): bool
    {
        return !static::isNonAssociative($arr);
    }
}
