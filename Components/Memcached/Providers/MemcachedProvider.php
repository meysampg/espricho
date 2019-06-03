<?php

namespace Espricho\Components\Memcached\Providers;

use Doctrine\Common\Cache\MemcachedCache;
use Espricho\Components\Application\System;
use Symfony\Component\DependencyInjection\Reference;
use Espricho\Components\Contracts\MemcachedInterface;
use Symfony\Component\Cache\Adapter\MemcachedAdapter;
use Espricho\Components\Providers\AbstractServiceProvider;

use function explode;
use function sprintf;

/**
 * Class MemcachedProvider provides redis adaptor
 *
 * @package Espricho\Components\Memcached\Providers
 */
class MemcachedProvider extends AbstractServiceProvider
{
    protected $dependencies = [
         MemcachedEnvToConfigProvider::PROVIDE => MemcachedEnvToConfigProvider::class,
    ];

    /**
     * @inheritDoc
     */
    public function register(System $system)
    {
        $system->register(MemcachedInterface::class)
               ->setFactory([MemcachedAdapter::class, 'createConnection'])
               ->setArguments(
                    [
                         $this->getMemcachedDsn($system),
                         $this->getConfigurationOptions($system),
                    ]
               )
        ;

        $system->register(MemcachedCache::class, MemcachedCache::class)
               ->setArguments([new Reference(MemcachedInterface::class)])
        ;
    }

    /**
     * Return DSN for memcached servers
     *
     * @param System $system
     *
     * @return array
     */
    private function getMemcachedDsn(System $system): array
    {
        $servers = $system->getConfig('memcached.servers', '127.0.0.1:11222');
        $servers = explode(',', $servers);
        $results = [];

        foreach ($servers as $server) {
            $results[] = sprintf('memcached://%s', trim($server));
        }

        return $results;
    }

    /**
     * Return memcached client configuration options
     *
     * @param System $system
     *
     * @return array
     */
    private function getConfigurationOptions(System $system): array
    {
        return [
             'auto_eject_hosts'       => $system->getConfig('auto_eject_hosts', false),
             'buffer_writes'          => $system->getConfig('buffer_writes', false),
             'compression'            => $system->getConfig('compression', false),
             'compression_type'       => $system->getConfig('compression_type', 'zlib'),
             'connect_timeout'        => $system->getConfig('connect_timeout', 1000),
             'distribution'           => $system->getConfig('distribution', 'consistent'),
             'hash'                   => $system->getConfig('hash', 'md5'),
             'libketama_compatible'   => $system->getConfig('libketama_compatible', true),
             'no_block'               => $system->getConfig('no_block', true),
             'number_of_replicas'     => $system->getConfig('number_of_replicas', 0),
             'prefix_key'             => $system->getConfig('prefix_key', ''),
             'poll_timeout'           => $system->getConfig('poll_timeout', 1000),
             'randomize_replica_read' => $system->getConfig('randomize_replica_read', false),
             'recv_timeout'           => $system->getConfig('recv_timeout', 0),
             'retry_timeout'          => $system->getConfig('retry_timeout', 0),
             'send_timeout'           => $system->getConfig('send_timeout', 0),
             'serializer'             => $system->getConfig('serializer', 'php'),
             'server_failure_limit'   => $system->getConfig('server_failure_limit', 0),
             'socket_recv_size'       => $system->getConfig('socket_recv_size', 0),
             'socket_send_size'       => $system->getConfig('socket_send_size', 0),
             'tcp_keepalive'          => $system->getConfig('tcp_keepalive', false),
             'tcp_nodelay'            => $system->getConfig('tcp_nodelay', false),
             'use_udp'                => $system->getConfig('use_udp', false),
             'verify_key'             => $system->getConfig('verify_key', false),
        ];
    }
}
