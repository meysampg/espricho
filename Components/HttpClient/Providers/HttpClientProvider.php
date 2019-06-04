<?php

namespace Espricho\Components\HttpClient\Providers;

use GuzzleHttp\Client;
use Espricho\Components\Application\System;
use Espricho\Components\Contracts\HttpClientInterface;
use Espricho\Components\Providers\AbstractServiceProvider;

/**
 * Class HttpClientProvider provides a http client service
 *
 * @package Espricho\Components\HttpClient\Providers
 */
class HttpClientProvider extends AbstractServiceProvider
{
    /**
     * @inheritDoc
     */
    public function register(System $system)
    {
        $system->register(HttpClientInterface::class, Client::class)
               ->setPublic(true)
        ;

        $system->setAlias('http-client', HttpClientInterface::class)
               ->setPublic(true)
        ;
    }
}
