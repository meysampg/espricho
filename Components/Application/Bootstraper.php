<?php

namespace Espricho\Components\Application;

use Exception;
use Symfony\Component\Debug\Debug;
use Espricho\Components\Singletons\EnvLoader;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Espricho\Components\Application\Exceptions\NotEnvFileExistsException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

use function rtrim;
use function sprintf;
use function realpath;
use function in_array;

/**
 * Class Bootstraper starter of the system
 *
 * @package Espricho\Components\Application
 */
abstract class Bootstraper
{
    /**
     * The system instance
     *
     * @var System
     */
    protected $system;

    /**
     * Indicate system is booted or not
     *
     * @var bool
     */
    protected $isBooted = false;

    /**
     * Bootstraper constructor.
     *
     * @param string $envPath
     *
     * @throws NotEnvFileExistsException
     */
    public function __construct(string $envPath)
    {
        $envPath = realpath(rtrim($envPath, DIRECTORY_SEPARATOR . "/") . DIRECTORY_SEPARATOR . ".env");

        try {
            EnvLoader::getInstance()->loadEnv($envPath, 'ENV');
        } catch
        (Exception $e) {
            throw new NotEnvFileExistsException(sprintf('At least one of .env or .env.dist files must be exist on %s.', $envPath));
        }

        /**
         * Create the application!
         */
        $this->startBoot();
    }

    /**
     * @return System
     */
    public function getSystem(): System
    {
        return $this->system;
    }

    /**
     * @param System $system
     */
    public function setSystem(System $system): void
    {
        $this->system = $system;
    }

    /**
     * Returns an array of FQN extensions provider classes
     *
     * @return array
     */
    abstract public function extensions(): array;

    /**
     * List of service providers in FQN format which are essential to booting process
     *
     * @return array
     */
    abstract public function serviceProviders(): array;

    /**
     * A key-value list of parameters to inject into system on boot process
     *
     * @return array
     */
    abstract public function bootParameters(): array;

    /**
     * Boot the application
     */
    protected function startBoot()
    {
        if ($this->isBooted()) {
            return;
        }

        $this->debuggerInitialize();
        $this->runSystemProcess();
        $this->registerExtensions();
        $this->registerServices();

        $this->makeBooted();
    }

    /**
     * Check bootstrap is done or not
     *
     * @return bool
     */
    protected function isBooted(): bool
    {
        return $this->isBooted;
    }

    /**
     * Make bootstrap process done
     */
    protected function makeBooted()
    {
        $this->isBooted = true;
    }

    /**
     * Clear state of boot to the ready to boot
     */
    protected function makeReadyToBoot()
    {
        $this->isBooted = false;
    }

    /**
     * Start the root system process as the main process of the system
     */
    protected function runSystemProcess()
    {
        $bag = $this->makeParameterBag();

        $this->setSystem(new System($bag));
    }

    /**
     * Parameter bag which must be injected into the system
     *
     * @return null|ParameterBagInterface
     */
    protected function makeParameterBag(): ?ParameterBagInterface
    {
        $parameters = $this->bootParameters();
        if (empty($parameters)) {
            return null;
        }

        $bag = new ParameterBag();
        foreach ($parameters as $key => $value) {
            $bag->set($key, $value);
        }

        return $bag;
    }

    /**
     * Register extensions
     */
    protected function registerExtensions()
    {
        foreach ($this->extensions() as $extension) {
            $this->getSystem()->registerExtension($extension);
        }
    }

    /**
     * Register services
     */
    protected function registerServices()
    {
        foreach ($this->serviceProviders() as $service) {
            $this->getSystem()->registerServiceProvider($service);
        }
    }

    /**
     * Initialize debugger for developments environments
     */
    protected function debuggerInitialize()
    {
        if (in_array(env('app_env'), ['dev', 'debug', 'test'])) {
            Debug::enable();
        }
    }
}
