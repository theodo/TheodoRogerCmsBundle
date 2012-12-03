<?php

namespace Theodo\RogerCmsBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @author Marek Kalnik <marekk@theodo.fr>
 */
class ExistingLayout extends Constraint
{
    public $validationService = 'theodo_roger_cms.validator.existing_layout';

    public function validatedBy()
    {
        return $this->validationService;
    }
}
