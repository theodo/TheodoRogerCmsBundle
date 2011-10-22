<?php

namespace Theodo\RogerCmsBundle\Validator;

use Symfony\Component\Validator\Constraint;

class Exists extends Constraint
{
    public $message = '"%s" "%s" doesn\'t exist';
    public $entity;
    public $property;
   
    public function validatedBy()
    {
        return 'validator.exists';
    }
   
    public function requiredOptions()
    {
        return array('entity', 'property');
    }
   
    public function targets()
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
