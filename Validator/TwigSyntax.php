<?php

namespace Theodo\RogerCmsBundle\Validator;

use Symfony\Component\Validator\Constraint;

class TwigSyntax extends Constraint
{
    public $message = '"%s" "%s" doesn\'t exist';
    public $entity;
    public $property;
   
    public function validatedBy()
    {
        return 'validator.twig_syntax';
    }
   
    public function requiredOptions()
    {
        return array('twig_environment');
    }
   
    public function targets()
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
