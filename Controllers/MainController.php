<?php

namespace Espricho\Controllers;

use Espricho\Components\Http\Controller;

/**
 * Class MainController shows a default index
 *
 * @package Espricho\Controllers
 */
class MainController extends Controller
{
    /**
     * Let's GO!
     *
     * @return array
     */
    public function index()
    {
        return [
             'name'    => app()->getConfig('app.name'),
             'version' => app()->getConfig('app.version'),
        ];
    }
}
