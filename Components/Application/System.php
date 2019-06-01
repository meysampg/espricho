<?php

namespace Espricho\Components\Application;

use Exception;
use Espricho\Components\Helpers\Os;
use Espricho\Components\Contracts\Middleware;
use Espricho\Components\Contracts\KernelInterface;
use Espricho\Components\Contracts\Authenticatable;
use Espricho\Components\Contracts\ApplicationInterface;
use Espricho\Components\Contracts\ConfigManagerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Espricho\Components\Http\Exceptions\InvalidMiddlewareClassException;

use function is_a;
use function sprintf;
use function in_array;
use function realpath;
use function is_string;
use function str_replace;

/**
 * Class System provides the operation system functionality. It provides the
 * communication between the event manager, config manager and the kernel.
 *
 * @package Espricho\Components\System
 */
class System extends ContainerBuilder implements ApplicationInterface
{
    /**
     * Defines service providers
     *
     * @var array
     */
    protected $serviceProviders = [];

    /**
     * Store the authenticated user model
     *
     * @var Authenticatable
     */
    protected $authenticated_user;

    /**
     * Store a list of middlewares
     *
     * @var array
     */
    protected $middlewares = [];

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
     * Convert a given parameter holder to container aware key
     *
     * @param string $s
     *
     * @return string
     */
    public function getParameterHolder(string $s): string
    {
        return sprintf("%%%s%%", $s);
    }

    /**
     * Authenticated user setter
     *
     * @param Authenticatable $user
     *
     * @return System
     */
    public function setUser(Authenticatable $user): System
    {
        $this->authenticated_user = $user;

        return $this;
    }

    /**
     * Authenticated user getter
     *
     * @return Authenticatable
     */
    public function getUser(): Authenticatable
    {
        return $this->authenticated_user;
    }

    /**
     * Return all middlewares
     * TODO: implement a mechanism for separating route middlewares and global middlewares
     *
     * @return array
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    /**
     * Get a requested middleware
     *
     * @param string $key
     *
     * @return Middleware|null
     */
    public function getMiddleware(string $key): ?Middleware
    {
        if (!isset($this->middlewares[$key])) {
            return null;
        }

        $middleware = $this->middlewares[$key];

        return (is_string($middleware) ? new $middleware : $middleware);
    }

    /**
     * Set a middleware
     *
     * @param string            $alias
     * @param Middleware|string $middleware
     *
     * @throws InvalidMiddlewareClassException
     */
    public function setMiddleware(string $alias, $middleware): void
    {
        if (!is_a($middleware, Middleware::class, true)) {
            throw new InvalidMiddlewareClassException(sprintf("%s is not a valid middleware class. Make sure it implements %s.", $middleware, Middleware::class));
        }

        if (is_string($middleware)) {
            $middleware = new $middleware;
        }

        $this->middlewares[$alias] = $middleware;
    }

    /**
     * Get absolute path from the root of project
     *
     * @param string $path
     *
     * @return string
     */
    public function getPath(string $path = ''): string
    {
        return $this->getProjectDir() . DIRECTORY_SEPARATOR . trim($path, '/\\' . DIRECTORY_SEPARATOR);
    }

    /**
     * Get root of project
     *
     * @return string
     */
    public function getProjectDir(): string
    {
        return Os::getPathBasedOnTheFile($this, 'composer.json');
    }

    /**
     * @inheritdoc
     */
    public function getEventManager(): EventDispatcherInterface
    {
        return $this->get(EventDispatcherInterface::class);
    }

    /**
     * @inheritdoc
     */
    public function setEventManager(EventDispatcherInterface $eventManager)
    {
        $this->set(EventDispatcherInterface::class, $eventManager);
    }

    /**
     * @inheritdoc
     */
    public function getConfigManager(): ConfigManagerInterface
    {
        return $this->get(ConfigManagerInterface::class);
    }

    /**
     * @inheritdoc
     */
    public function setConfigManager(ConfigManagerInterface $configManager)
    {
        $this->set(ConfigManagerInterface::class, $configManager);
    }

    /**
     * @inheritdoc
     */
    public function getKernel(): KernelInterface
    {
        return $this->get(KernelInterface::class);
    }

    /**
     * @inheritdoc
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->set(KernelInterface::class, $kernel);
    }

    /**
     * Return a requested config
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getConfig(string $key, $default = null)
    {
        return $this->getConfigManager()->get($key, $default);
    }

    /**
     * Indicate application is in development mode or not
     *
     * @return bool
     */
    public function isDevMode(): bool
    {
        return in_array($this->getConfig('sys.env'), ['test', 'dev', 'local']);
    }
}
