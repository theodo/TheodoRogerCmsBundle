<?php

namespace Sadiant\CmsBundle\Form;

use Symfony\Component\Form\FormBuilder;
use Doctrine\ORM\EntityRepository;

use Sadiant\CmsBundle\Form\UserPreferencesType;
use Sadiant\CmsBundle\Entity\User;
use Sadiant\CmsBundle\Entity\Role;
use Sadiant\CmsBundle\Repository\UserRepository;

class UserType extends UserPreferencesType
{
    protected $is_new;

    /**
     * Form constructor
     * 
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-27
     */
    public function __construct($is_new = true)
    {
        $this->is_new = $is_new;
    }
    
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
        $builder->add('user_roles', 'entity', array(
            'class'    => 'Sadiant\\CmsBundle\\Entity\\Role',
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
           'data_class' => 'Sadiant\CmsBundle\Entity\User',
        );
    }
}
