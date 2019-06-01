<?php

namespace Espricho\Components\Console\Providers;

use Espricho\Components\Application\System;
use Espricho\Components\Providers\AbstractServiceProvider;
use Symfony\Component\Console\Question\Question;

class QuestionProvider extends AbstractServiceProvider
{
    /**
     * @inheritdoc
     */
    public function register(System $app)
    {
        $app->register(Question::class, Question::class);
    }
}
