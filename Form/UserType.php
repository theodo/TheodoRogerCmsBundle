<?php

namespace Theodo\RogerCmsBundle\Form;

use Symfony\Component\Form\FormBuilder;
use Doctrine\ORM\EntityRepository;

use Theodo\RogerCmsBundle\Form\UserPreferencesType;
use Theodo\RogerCmsBundle\Entity\User;
use Theodo\RogerCmsBundle\Entity\Role;
use Theodo\RogerCmsBundle\Repository\UserRepository;

class UserType extends UserPreferencesType
{
    /**
     * Form builder
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-27
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        parent::buildForm($builder, $options);

        // Set inputs
        $builder->add('notes', 'textarea', array('required' => false));
        if (null === $options['data']->getPassword()) {
            $builder->add('password', 'repeated', array('type' => 'password', 'required' => true));
        }
        $builder->add('user_roles', 'entity', array(
            'class'    => 'Theodo\\RogerCmsBundle\\Entity\\Role',
            'expanded' => true,
            'multiple' => true,
            'required' => true,
            'em' => $options['em'],
        ));
    }

    /**
     * Form default options
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-27
     */
    public function getDefaultOptions()
    {
        return array(
           'data_class' => 'Theodo\RogerCmsBundle\Entity\User',
           'em' => null,
        );
    }

    public function getName()
    {
        return 'user';
    }
}
