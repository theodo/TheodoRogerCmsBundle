<?php

namespace Theodo\RogerCmsBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TwigSyntaxValidator extends ConstraintValidator
{

    public function __construct($twig_environment)
    {
        $this->twig = $twig_environment;
    }

    /**
     * Validation function
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-29
     */
    public function isValid($value, Constraint $constraint)
    {
        try {
            $this->twig->parse($this->twig->tokenize($value));

            return true;
        } catch (\Twig_Error_Syntax $e) {
            $this->setMessage($e->getMessage());

            return false;
        }
    }
}
