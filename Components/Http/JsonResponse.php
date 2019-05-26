<?php

namespace Espricho\Components\Http;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class JsonResponse provides returning JSON response mechanism
 *
 * @package Espricho\Components\Http
 */
class JsonResponse extends Response
{
    public function __construct(string $content = '', int $status = 200, array $headers = [])
    {
        // set response content-type = json

        parent::__construct($content, $status, $headers);
    }
}
