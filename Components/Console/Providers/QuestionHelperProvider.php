<?php

namespace Espricho\Components\Console\Providers;

use Espricho\Components\Application\System;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Helper\QuestionHelper;
use Espricho\Components\Providers\AbstractServiceProvider;

class QuestionHelperProvider extends AbstractServiceProvider
{
    protected $dependencies = [
         Question::class => QuestionProvider::class,
    ];

    /**
     * @inheritdoc
     */
    public function register(System $app)
    {
        $app->register(QuestionHelper::class, QuestionHelper::class)
            ->addArgument(Question::class)
        ;
    }
}
