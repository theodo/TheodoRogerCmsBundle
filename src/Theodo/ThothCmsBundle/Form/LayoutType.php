<?php

namespace Theodo\ThothCmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

use Theodo\ThothCmsBundle\Entity\Layout;

class LayoutType extends AbstractType
{

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('id', 'hidden');
        $builder->add('name', 'text', array('required' => true));
        $builder->add('content', 'textarea', array('required' => true));
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Theodo\ThothCmsBundle\Entity\Layout',
        );
    }

}