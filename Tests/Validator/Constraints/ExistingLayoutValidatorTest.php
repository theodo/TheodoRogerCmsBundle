<?php

namespace Theodo\RogerCmsBundle\Tests\Validator\Constraints;

use Theodo\RogerCmsBundle\Validator\Constraints\ExistingLayoutValidator;
use Phake;

/**
 * @author Marek Kalnik <marekk@theodo.fr>
 */
class ExistingLayoutValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Twig_LoaderInterface
     */
    public $loader;

    /**
     * @var Theodo\RogerCmsBundle\Validator\Constraints\ExistingLayoutValidator
     */
    public $validator;

    /**
     * @var Theodo\RogerCmsBundle\Form\DataTransformer\LayoutExtractor;
     */
    public $extractor;

    public function setUp()
    {
        $this->loader = Phake::mock('Twig_LoaderInterface');
        $this->extractor = Phake::mock('Theodo\RogerCmsBundle\Form\DataTransformer\LayoutExtractor');
        $this->validator = new ExistingLayoutValidator($this->loader, $this->extractor);
    }

    public function testIsConstraintValidator()
    {
        $this->assertInstanceOf('Symfony\Component\Validator\ConstraintValidatorInterface', $this->validator);
    }

    public function testValidatesIfLayoutExists()
    {
        Phake::when($this->extractor)->getLayout(Phake::anyParameters())
            ->thenReturn('blabla');
        Phake::when($this->loader)->getSource(Phake::anyParameters())
            ->thenReturn('blabla');

        $context = Phake::mock('Symfony\Component\Validator\ExecutionContext');
        $this->validator->initialize($context);

        $this->validator->validate('blabla', Phake::mock('Symfony\Component\Validator\Constraint'));

        Phake::verify($this->extractor, Phake::times(1))
            ->getLayout(Phake::anyParameters());
        Phake::verify($this->loader, Phake::times(1))
            ->getSource(Phake::anyParameters());
        Phake::verify($context, Phake::never())
            ->addViolation(Phake::anyParameters());
    }

    public function testFailsIfLayoutDoesNotExist()
    {
        Phake::when($this->extractor)->getLayout(Phake::anyParameters())
            ->thenReturn('blabla');
        Phake::when($this->loader)->getSource(Phake::anyParameters())
            ->thenThrow(new \Twig_Error_Loader('Template not found'));

        $context = Phake::mock('Symfony\Component\Validator\ExecutionContext');
        $this->validator->initialize($context);
        $this->validator->validate('blabla', Phake::mock('Symfony\Component\Validator\Constraint'));

        Phake::verify($this->loader, Phake::times(1))
            ->getSource(Phake::anyParameters());
        Phake::verify($context, Phake::times(1))
            ->addViolation(Phake::anyParameters());
    }
}
