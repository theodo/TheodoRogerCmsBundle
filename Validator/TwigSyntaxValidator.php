<?php

namespace Theodo\RogerCmsBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TwigSyntaxValidator extends ConstraintValidator
{
    /**
     * {@inheritDoc}
     *
     * @param $twig_environment
     */
    public function __construct(\Twig_Environment $twig_environment)
    {
        $this->twig = $twig_environment;
    }

    /**
     * {@inheritDoc}
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-29
     */
    public function validate($value, Constraint $constraint)
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
