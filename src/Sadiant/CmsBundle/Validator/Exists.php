<?php

namespace Sadiant\CmsBundle\Validator;

use Symfony\Component\Validator\Constraint;

class Exists extends Constraint
{
    public $message = '"%s" "%s" does\'nt exists';
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
