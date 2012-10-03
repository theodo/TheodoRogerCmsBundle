<?php

namespace Theodo\RogerCmsBundle\Extensions\Twig\Extension;

use Symfony\Component\Form\FormView;

class RogerHelperExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return array('error_in_fields' => new \Twig_Function_Method($this, 'errorInFields'));
    }

    /**
     * Checks if any of the fields passed by argument has errors
     * 
     * @param Symfony\Component\Form\FormView $form
     * @param array $fieldNames
     */
    public function errorInFields(FormView $form, array $fieldNames)
    {
        if (!$form->hasChildren()) {
            return false;
        }

        $fieldNames = array_flip($fieldNames);

        foreach ($form->getChildren() as $name => $field) {
            if (isset($fieldNames[$name]) && $form->getChild($name)->get('errors')) {
                return true;
            }
        }

        return false;
    }

    public function getName()
    {
        return 'roger_helper';
    }
}
