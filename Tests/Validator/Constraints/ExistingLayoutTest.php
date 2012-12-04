<?php

namespace Theodo\RogerCmsBundle\Tests\Validator\Constraints;

use Theodo\RogerCmsBundle\Validator\Constraints\ExistingLayout;

class ExistingLayoutTest extends \PHPUnit_Framework_TestCase
{
    public function testIsValidationConstraint()
    {
        $constraint = new ExistingLayout();
        $this->assertInstanceOf('\Symfony\Component\Validator\Constraint', $constraint);
    }

    public function testHasNotStandardValidator()
    {
        $constraint = new ExistingLayout();
        $this->assertNotEquals(get_class($constraint) . 'Validator', $constraint->validatedBy());
    }
}
