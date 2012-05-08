<?php

namespace Theodo\RogerCmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

use Theodo\RogerCmsBundle\Entity\Media;

class MediaType extends AbstractType
{

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('id', 'hidden');
        $builder->add('name', 'text', array('required' => true));
        $builder->add('file', 'file', array('required' => false));
    }

    public function getDefaultOptions()
    {
        return array(
            'data_class' => 'Theodo\RogerCmsBundle\Entity\Media',
        );
    }

    public function getName()
    {
        return 'media';
    }
}
