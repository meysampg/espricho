<?php

namespace Espricho\Components\Application\Providers;

use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Espricho\Components\Application\System;
use Espricho\Components\Providers\AbstractServiceProvider;

/**
 * Class LoggerProvider register monolog as the logger
 *
 * @package Espricho\Components\System\Providers
 */
class LoggerProvider extends AbstractServiceProvider
{
    /**
     * @inheritdoc
     */
    public function register(System $system)
    {
        $path    = $system->getPath('Runtime/Logs/espricho.log');
        $handler = new RotatingFileHandler($path, $system->getConfig('sys.max_log_files', 10), Logger::DEBUG);

        $format    = "[%datetime%] %channel%.%level_name%: %message%\n%context%\n%extra%\n\n";
        $formatter = new LineFormatter($format);
        $formatter->includeStacktraces();
        $formatter->ignoreEmptyContextAndExtra();

        $handler->setFormatter($formatter);

        $system->register(LoggerInterface::class, Logger::class)
               ->setArguments(['Espricho', [$handler]])
               ->setPublic(true)
        ;
    }
}
