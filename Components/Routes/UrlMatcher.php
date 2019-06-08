<?php

namespace Espricho\Components\Routes;

use Espricho\Components\Contracts\HttpKernelEvent;
use Espricho\Components\Routes\Events\RouteResolvedEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\Matcher\UrlMatcher as BaseUrlMatcher;
use Symfony\Component\Routing\Matcher\RedirectableUrlMatcherInterface;

class UrlMatcher extends BaseUrlMatcher
{
    /**
     * @inheritdoc
     */
    protected function matchCollection($pathinfo, $routes)
    {
        // HEAD and GET are equivalent as per RFC
        if ('HEAD' === $method = $this->context->getMethod()) {
            $method = 'GET';
        }
        $supportsTrailingSlash = 'GET' === $method && $this instanceof RedirectableUrlMatcherInterface;
        $trimmedPathinfo       = rtrim($pathinfo, '/') ?: '/';

        foreach ($routes as $name => $route) {
            $compiledRoute   = $route->compile();
            $staticPrefix    = rtrim($compiledRoute->getStaticPrefix(), '/');
            $requiredMethods = $route->getMethods();

            // check the static prefix of the URL first. Only use the more expensive preg_match when it matches
            if ('' !== $staticPrefix && 0 !== strpos($trimmedPathinfo, $staticPrefix)) {
                continue;
            }
            $regex = $compiledRoute->getRegex();

            $pos              = strrpos($regex, '$');
            $hasTrailingSlash = '/' === $regex[$pos - 1];
            $regex            = substr_replace($regex, '/?$', $pos - $hasTrailingSlash, 1 + $hasTrailingSlash);

            if (!preg_match($regex, $pathinfo, $matches)) {
                continue;
            }

            $hasTrailingVar = $trimmedPathinfo !== $pathinfo && preg_match('#\{\w+\}/?$#', $route->getPath());

            if ($hasTrailingVar && ($hasTrailingSlash || (null === $m = $matches[\count($compiledRoute->getPathVariables())] ?? null) || '/' !== ($m[-1] ?? '/')) && preg_match($regex, $trimmedPathinfo, $m)) {
                if ($hasTrailingSlash) {
                    $matches = $m;
                } else {
                    $hasTrailingVar = false;
                }
            }

            $hostMatches = [];
            if ($compiledRoute->getHostRegex() && !preg_match($compiledRoute->getHostRegex(), $this->context->getHost(), $hostMatches)) {
                continue;
            }

            $status = $this->handleRouteRequirements($pathinfo, $name, $route);

            if (self::REQUIREMENT_MISMATCH === $status[0]) {
                continue;
            }

            if ('/' !== $pathinfo && !$hasTrailingVar && $hasTrailingSlash === ($trimmedPathinfo === $pathinfo)) {
                if ($supportsTrailingSlash && (!$requiredMethods || \in_array('GET', $requiredMethods))) {
                    return $this->allow = $this->allowSchemes = [];
                }

                continue;
            }

            $hasRequiredScheme = !$route->getSchemes() || $route->hasScheme($this->context->getScheme());
            if ($requiredMethods) {
                if (!\in_array($method, $requiredMethods)) {
                    if ($hasRequiredScheme) {
                        $this->allow = array_merge($this->allow, $requiredMethods);
                    }

                    continue;
                }
            }

            if (!$hasRequiredScheme) {
                $this->allowSchemes = array_merge($this->allowSchemes, $route->getSchemes());

                continue;
            }

            service(EventDispatcherInterface::class)->dispatch(HttpKernelEvent::ROUTE_MATCHED, new RouteResolvedEvent($route, $this->request));

            return $this->getAttributes($route, $name, array_replace($matches, $hostMatches, isset($status[1]) ? $status[1] : []));
        }

        return [];
    }
}
