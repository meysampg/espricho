<?php

namespace Espricho\Components\Application;

use Espricho\Components\Contracts\ModuleInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Module provides the module functionality
 *
 * @package Espricho\Components\Application
 */
class Module implements ModuleInterface
{
    /**
     * Normalize a given module information array
     *
     * @param string $name
     * @param array  $info
     *
     * @return array
     */
    public static function infoNormalizer(string $name, array $info): array
    {
        $default = [
             "name"         => $name,
             "folder"       => null,
             "route_prefix" => null,
             "modules"      => null,
        ];

        $resolver = new OptionsResolver();
        $resolver->setDefaults($default);

        return $resolver->resolve($info);
    }
}
