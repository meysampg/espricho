<?php

namespace Espricho\Components\Providers;

use stdClass;
use Espricho\Components\Application\System;

/**
 * Class EnvToConfigProvider provides service of converting environmental variables
 * to the system layer configurations. It converts all given key-value in `[SECTION_
 * SUBSECTION_VARIABLE => value]` format to a configuration like `section.subsection
 * .variable` which in that subsection can be optional or repeated multiple times.
 * For example `DB_USERNAME` will be converted into `db.username`.
 *
 * @package Espricho\Components\Providers
 */
abstract class EnvToConfigProvider extends AbstractServiceProvider
{
    public const PROVIDE = null;

    /**
     * List of keys of environmental variables
     *
     * @var array
     */
    protected $env = [];

    /**
     * @inheritdoc
     */
    public function register(System $system)
    {
        foreach ((array)$this->env as $key) {
            $system->getConfigManager()->addRaw($key, env($key));
        }

        $system->set(static::PROVIDE, new stdClass());
    }
}
