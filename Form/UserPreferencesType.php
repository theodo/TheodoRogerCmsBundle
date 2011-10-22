<?php

namespace Theodo\RogerCmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Doctrine\ORM\EntityRepository;

use Theodo\RogerCmsBundle\Entity\User;
use Theodo\RogerCmsBundle\Entity\Role;
use Theodo\RogerCmsBundle\Repository\UserRepository;

class UserPreferencesType extends AbstractType
{
    /**
     * Form builder
     * 
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-27
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        // Set inputs
        $builder->add('name', 'text', array('required' => true));
        $builder->add('username', 'text', array('required' => true));
        $builder->add('email', 'text', array('required' => true));

        $builder->add('password', 'repeated', array('type' => 'password', 'required' => false));
        
        $builder->add('language', 'choice', array(
            'choices'   => UserRepository::getAvailableLanguages(),
            'required'  => false
        ));

        // Salt hidden field
        if (null === $options['data']->getPassword()) {
            $builder->add('salt', 'hidden', array('required' => true));
        }
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
           'data_class' => 'Theodo\RogerCmsBundle\Entity\User',
        );
    }

    public function getName()
    {
        return 'user_preferences';
    }
}
