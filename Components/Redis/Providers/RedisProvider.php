<?php

namespace Espricho\Components\Redis\Providers;

use Doctrine\Common\Cache\PredisCache;
use Doctrine\Common\Cache\RedisCache;
use Espricho\Components\Application\System;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Espricho\Components\Contracts\RedisInterface;
use Symfony\Component\DependencyInjection\Reference;
use Espricho\Components\Providers\AbstractServiceProvider;

use function sprintf;

/**
 * Class RedisProvider provides redis adaptor
 *
 * @package Espricho\Components\Redis\Providers
 */
class RedisProvider extends AbstractServiceProvider
{
    protected $dependencies = [
         RedisEnvToConfigProvider::PROVIDE => RedisEnvToConfigProvider::class,
    ];

    /**
     * @inheritDoc
     */
    public function register(System $system)
    {
        $system->register(RedisInterface::class)
               ->setFactory([RedisAdapter::class, 'createConnection'])
               ->setArguments(
                    [
                         $this->getRedisDsn($system),
                         $this->getConfigurationOptions($system),
                    ]
               )
        ;

        $system->register(RedisCache::class, PredisCache::class)
               ->setArguments([new Reference(RedisInterface::class)])
        ;
    }

    /**
     * Return DSN for redis server
     *
     * @param System $system
     *
     * @return string
     */
    private function getRedisDsn(System $system): string
    {
        $server = $system->getConfig('redis.server', '127.0.0.1');
        $port   = $system->getConfig('redis.port', '6379');

        if (($password = $system->getConfig('redis.password', false)) != false) {
            return sprintf('redis://%s@%s:%s', $password, $server, $port);
        }

        return sprintf('redis://%s:%s', $server, $port);
    }

    /**
     * Return redis client configuration options
     *
     * @param System $system
     *
     * @return array
     */
    private function getConfigurationOptions(System $system): array
    {
        return [
             'compression'    => $system->getConfig('redis.compression', true),
             'lazy'           => $system->getConfig('redis.lazy', false),
             'read_timeout'   => $system->getConfig('redis.read_timeout', 0),
             'retry_interval' => $system->getConfig('redis.retry_interval', 0),
             'tcp_keepalive'  => $system->getConfig('redis.tcp_keepalive', 0),
             'timeout'        => $system->getConfig('redis.timeout', 30),
        ];
    }
}
