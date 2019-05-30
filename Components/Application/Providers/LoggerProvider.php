<?php

namespace Espricho\Components\Application\Providers;

use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Monolog\Handler\StreamHandler;
use Espricho\Components\Application\Application;
use Symfony\Component\DependencyInjection\Reference;
use Espricho\Components\Providers\AbstractServiceProvider;

use function sprintf;
use function str_replace;

/**
 * Class LoggerProvider register monolog as the logger
 *
 * @package Espricho\Components\Application\Providers
 */
class LoggerProvider extends AbstractServiceProvider
{
    /**
     * @inheritdoc
     */
    public function register(Application $app)
    {
        $path = $app->getPath('Runtime/Logs/espricho.log');
        $app->register(StreamHandler::class, StreamHandler::class)
            ->setArguments([$path, Logger::DEBUG])
        ;
        $app->register(LoggerInterface::class, Logger::class)
            //->setArguments(['Espricho', [new Reference(StreamHandler::class)]])
        ;
    }
}
