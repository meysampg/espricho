<?php

namespace Espricho\Components\Helpers;

use ReflectionObject;

use function dirname;
use function realpath;
use function file_exists;

/**
 * Class Os provides working with operating system functions
 *
 * @package Espricho\Components\Helpers
 */
class Os
{
    /**
     * Get a directory path, based on a given object. It search the given path and
     * if the directory of the object contains the given file, it returns, otherwise
     * if level up until find the file.
     *
     * @param object $obj
     * @param string $file
     *
     * @return string
     */
    public static function getPathBasedOnTheFile(object $obj, string $file): string
    {
        $relection = new ReflectionObject($obj);
        $filePath  = realpath($relection->getFileName());
        $dir       = dirname($filePath);

        while (!file_exists($dir . DIRECTORY_SEPARATOR . $file)) {
            if ($dir === dirname($dir)) { // it's the root
                return $dir;
            }

            $dir = dirname($dir);
        }

        return $dir;
    }
}
