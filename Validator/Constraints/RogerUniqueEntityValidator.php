<?php
namespace Theodo\RogerCmsBundle\Validator\Constraints;

use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineConstraints;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * This class is a correction to symfony entity validator
 * Which takes the default entity manager instead of entity's own manager
 * causing errors with multiple EMs.
 *
 * A pull request has been made to sf2.1
 *
 * @author Marek Kalnik <marekk@theodo.fr>
 */
class RogerUniqueEntityValidator extends DoctrineConstraints\UniqueEntityValidator
{
    /**
     * @var RegistryInterface
     */
    private $registry;

    /**
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @see UniqueEntityValidator
     */
    public function isValid($entity, Constraint $constraint)
    {
        if (!is_array($constraint->fields) && !is_string($constraint->fields)) {
            throw new UnexpectedTypeException($constraint->fields, 'array');
        }

        $fields = (array)$constraint->fields;

        if (count($fields) == 0) {
            throw new ConstraintDefinitionException("At least one field has to be specified.");
        }

        if ($constraint->em) {
            $em = $this->registry->getEntityManager($constraint->em);
        } else {
            $em = $this->registry->getEntityManagerForClass(get_class($entity));
        }

        $className = $this->context->getCurrentClass();
        $class = $em->getClassMetadata($className);

        $criteria = array();
        foreach ($fields as $fieldName) {
            if (!isset($class->reflFields[$fieldName])) {
                throw new ConstraintDefinitionException("Only field names mapped by Doctrine can be validated for uniqueness.");
            }

            $criteria[$fieldName] = $class->reflFields[$fieldName]->getValue($entity);

            if ($criteria[$fieldName] === null) {
                return true;
            }
        }

        $repository = $em->getRepository($className);

        // strange bug when using the Translatable extension: Entity gets refreshed and never updated. To avoid this we created our own validator. But it works only with id as primary key.
        $qb = $repository->createQueryBuilder('e');
        if ($entity->getId()) {
            $orx = $qb->expr()->orx();
            foreach($class->identifier as $identifier) {
                $orx->add($qb->expr()->neq('e.'.$identifier, $class->reflFields[$identifier]->getValue($entity)));
            }
            $qb = $qb->andWhere($orx);
        }
        foreach ($criteria as $column => $value) {
            $qb = $qb->andWhere($qb->expr()->eq('e.'.$column, ':'.$column));
            $qb->setParameter($column, $value);
        }
        $qb->setMaxResults(1);

        $result = $qb->getQuery()->getArrayResult();

        if (0 == count($result)) {
            return true;
        }

        $oldPath = $this->context->getPropertyPath();
        $this->context->setPropertyPath( empty($oldPath) ? $fields[0] : $oldPath.".".$fields[0]);
        $this->context->addViolation($constraint->message, array(), $criteria[$fields[0]]);
        $this->context->setPropertyPath($oldPath);

        return true; // all true, we added the violation already!
    }
}