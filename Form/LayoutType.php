<?php

namespace Theodo\RogerCmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

use Theodo\RogerCmsBundle\Entity\Layout;

class LayoutType extends AbstractType
{

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('id', 'hidden');
        $builder->add('name', 'text', array('required' => true));
        $builder->add('content', 'textarea', array('required' => false));
    }

    public function getDefaultOptions()
    {
        return array(
            'data_class' => 'Theodo\RogerCmsBundle\Entity\Layout',
        );
    }

    public function getName()
    {
        return 'layout';
    }
}