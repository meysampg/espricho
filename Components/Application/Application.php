<?php

namespace Espricho\Components\Application;

use Exception;
use Symfony\Component\Debug\Debug;
use Espricho\Components\Configs\ConfigCollection;
use Espricho\Components\Contracts\KernelInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Espricho\Components\Configs\Traits\ConfigCommonMethodsTrait;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Application provides the core application class
 *
 * @package Espricho\Components\Application
 */
class Application extends ContainerBuilder
{
    use ConfigCommonMethodsTrait;

    /**
     * Defines service providers
     *
     * @var array
     */
    protected $serviceProviders = [];

    /**
     * Application constructor.
     *
     * @param ConfigCollection           $configs
     * @param ParameterBagInterface|null $parameterBag
     */
    public function __construct(ConfigCollection $configs, ParameterBagInterface $parameterBag = null)
    {
        $this->configs = $configs;

        date_default_timezone_set($configs->get('app.timezone', 'UTC'));
        $this->setDebugBehaviour();

        parent::__construct($parameterBag);
    }

    /**
     * fire up the application
     *
     * @return mixed
     * @throws Exception
     */
    public function fire()
    {
        // TODO: add app level before middleware support
        $response = $this->get(KernelInterface::class)->fire();

        // TODO: add app level after middleware support

        return $response;
    }

    /**
     * Register a service provider on the application
     *
     * @param string $provider
     */
    public function registerServiceProvider(string $provider)
    {
        if (isset($this->serviceProviders[$provider])) {
            return; // service is already loaded
        }

        (new $provider)->load($this);

        $this->serviceProviders[$provider] = true;
    }

    /**
     * Set the debugging behaviour
     */
    protected function setDebugBehaviour()
    {
        /**
         * Enable debug mode based on the configuration
         */
        if ($this->getConfig('app.debug', false)) {
            Debug::enable();
            ini_set('display_errors', 1);
            error_reporting(-1);
        }
    }
}
