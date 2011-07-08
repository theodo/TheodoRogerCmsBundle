<?php

namespace Theodo\ThothCmsBundle\Form;

use Symfony\Component\Form\FormBuilder;
use Doctrine\ORM\EntityRepository;

use Theodo\ThothCmsBundle\Form\UserPreferencesType;
use Theodo\ThothCmsBundle\Entity\User;
use Theodo\ThothCmsBundle\Entity\Role;
use Theodo\ThothCmsBundle\Repository\UserRepository;

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
            'class'    => 'Theodo\\ThothCmsBundle\\Entity\\Role',
            'expanded' => true,
            'multiple' => true,
            'required' => true
        ));
    }
   
    /**
     * Form default options
     * 
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-27
     */
    public function getDefaultOptions(array $options)
    {
        return array(
           'data_class' => 'Theodo\ThothCmsBundle\Entity\User',
        );
    }

    public function getName()
    {
        return 'user';
    }
}
