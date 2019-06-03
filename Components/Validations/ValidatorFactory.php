<?php

namespace Espricho\Components\Validations;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ValidatorFactory builds a Validator
 *
 * @package Espricho\Components\Validations
 */
class ValidatorFactory
{
    /**
     * Build a Validator
     *
     * @param bool $annotation
     *
     * @return ValidatorInterface
     */
    public static function build(bool $annotation = true): ValidatorInterface
    {
        $builder = Validation::createValidatorBuilder();
        if ($annotation) {
            $builder->enableAnnotationMapping();
        }

        return $builder->getValidator();
    }
}
