<?php

namespace Theodo\ThothCmsBundle\Validator;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ExistsValidator extends ConstraintValidator
{
    private $em;

    /**
     * Constuctor
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-22
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Validation function
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-22
     */
    public function isValid($value, Constraint $constraint)
    {
        // Try to retrieve entity with same property
        $entity = $this->em->getRepository($constraint->entity)->findOneBy(array($constraint->property => $value));

        // Check $entity
        if(!$entity) {
            // Validation fail, set message
            $this->setMessage(sprintf($constraint->message, $constraint->property, $value));

            return false;
        }

        return true;
    }
}
