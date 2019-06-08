<?php

namespace Espricho\Components\Application;

use Espricho\Components\DS\Stack;
use Espricho\Components\Contracts\Middleware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use function is_null;

/**
 * Class Onion provides a middleware running functionality
 *
 * @package Espricho\Components\System
 */
class Onion implements Middleware
{
    /**
     * Layers of middleware which must be run
     *
     * @var Stack
     */
    protected $layers;

    /**
     * Run a given set of middlewares
     *
     * @param array    $mws
     * @param Request  $request
     * @param callable $next
     *
     * @return null|Response
     */
    public static function run(array $mws, Request $request, callable $next = null)
    {
        $onion = new static($mws);

        if (is_null($next)) {
            $next = function (Request $request) {
                // the inner layer do nothing
            };
        }

        return $onion->handle(
             $request,
             $next
        );
    }

    /**
     * Onion constructor.
     *
     * @param array $mws
     */
    public function __construct(array $mws = [])
    {
        $this->initializeStack();
        $this->generateStack($mws);
    }

    /**
     * On Onion class the handle function generate the whole of the onion. It puts
     * the given $next function as the core and with each pop from the stack, it
     * make a layer around the previous layer. Finally it run the generated onion on
     * the given $request object;
     * |-- $r, $f1 ------------------------------------------|
     * |            |-- $r, $f2 ------------------------|    |
     * |            |             |-- $r, $f3 ------|   |    |
     * | return $f1(| return $f2 (| return $f3($r); |); |);  |
     * |            |             |-----------------|   |    |
     * |            |-----------------------------------|    |
     * |-----------------------------------------------------|
     *
     * @inheritdoc
     */
    public function handle(Request $request, callable $next): ?Response
    {
        $core = $this->generateCore($next);

        /** @var $middleware Middleware */
        foreach ($this->layers as $middleware) {
            $core = function (Request $request) use ($middleware, $core) {
                return $middleware->handle($request, $core);
            };
        }

        return $core($request);
    }

    /**
     * Generate stack from a given set of middleware
     *
     * @param array $mws Array of middleware
     */
    public function generateStack(array $mws)
    {
        foreach ($mws as $middleware) {
            $this->layers->push($middleware);
        }
    }

    /**
     * Initialize stack
     */
    protected function initializeStack()
    {
        $this->layers = new Stack();
    }

    /**
     * Generate the Onion core
     *
     * @param callable $f
     *
     * @return callable
     */
    protected function generateCore(callable $f): callable
    {
        return function ($request) use ($f) {
            return $f($request);
        };
    }
}
