<?php

namespace Espricho\Components\Console;

use Symfony\Component\Console\Application;
use Espricho\Components\Contracts\ConsoleKernelInterface;
use Espricho\Components\Configs\Traits\ConfigCommonMethodsTrait;

class Console extends Application implements ConsoleKernelInterface
{
    use ConfigCommonMethodsTrait;

    public function fire()
    {
        return $this->run();
    }
}
