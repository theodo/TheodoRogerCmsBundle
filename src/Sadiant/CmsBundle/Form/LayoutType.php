<?php

namespace Sadiant\CmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

use Sadiant\CmsBundle\Entity\Layout;

class LayoutType extends AbstractType
{

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('name', 'text');
        $builder->add('content', 'text');
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Sadiant\CmsBundle\Entity\Layout',
        );
    }

}