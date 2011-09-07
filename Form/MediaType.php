<?php

namespace Theodo\ThothCmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

use Theodo\ThothCmsBundle\Entity\Media;

class MediaType extends AbstractType
{

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('id', 'hidden');
        $builder->add('name', 'text', array('required' => true));
        $builder->add('file', 'file', array('required' => false));
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Theodo\ThothCmsBundle\Entity\Media',
        );
    }

    public function getName()
    {
        return 'media';
    }
}
