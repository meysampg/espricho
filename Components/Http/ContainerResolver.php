<?php

namespace Espricho\Components\Http;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;

use function sys;

/**
 * Class ContainerResolver provides argument from the system container
 *
 * @package Espricho\Components\Http
 */
class ContainerResolver implements ArgumentValueResolverInterface
{
    /**
     * @inheritdoc
     */
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return sys()->has($argument->getType());
    }

    /**
     * @inheritdoc
     */
    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        yield sys()->get($argument->getType());
    }
}
