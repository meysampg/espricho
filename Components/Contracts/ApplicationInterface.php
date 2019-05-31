<?php

namespace Espricho\Components\Contracts;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Interface ApplicationInterface defines the structure of an application; The
 * schema of an application is like this
 * |----------------------------|
 * |  |---------------------|   |
 * |  |   Event Manager     |   |
 * |  |---------------------|   |
 * |                            |
 * |  |---------------------|   |
 * |  |   Config Manager    |   |
 * |  |---------------------|   |
 * |                            |
 * |  |---------------------|   |
 * |  |   Kernel            |   |
 * |  |---------------------|   |
 * |----------------------------|
 * The first part of the application is the event manager. It must register
 * some events and their subscriber and interact with them on case of event
 * fire! In fact it's the mediator part of application.
 * The config manager moderates the fetching application layer configurations
 * and defines rules related to it. Kernel is the motor of the application.
 * Based on configurations, it interacts with the event manager and handle
 * requests and commands.
 *
 * @package Espricho\Components\Contracts
 */
interface ApplicationInterface
{
    /**
     * @return EventDispatcherInterface
     */
    public function getEventManager(): EventDispatcherInterface;

    /**
     * @param EventDispatcherInterface $eventManager
     */
    public function setEventManager(EventDispatcherInterface $eventManager);

    /**
     * @return ConfigManagerInterface
     */
    public function getConfigManager(): ConfigManagerInterface;

    /**
     * @param ConfigManagerInterface $configManager
     */
    public function setConfigManager(ConfigManagerInterface $configManager);

    /**
     * @return KernelInterface
     */
    public function getKernel(): KernelInterface;

    /**
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel);
}
