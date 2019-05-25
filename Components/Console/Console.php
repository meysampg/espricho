<?php

namespace Espricho\Components\Console;

use Symfony\Component\Console\Application;
use Espricho\Components\Contracts\ConsoleKernelInterface;

class Console extends Application implements ConsoleKernelInterface
{
    public function fire()
    {
        return $this->run();
    }
}
