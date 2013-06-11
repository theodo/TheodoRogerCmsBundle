<?php

namespace Theodo\RogerCmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Theodo\RogerCmsBundle\Entity\Media;

class MediaType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', 'hidden');
        $builder->add('name', 'text', array('required' => true));
        $builder->add('file', 'file', array('required' => false));
    }

    public function getDefaultOptions(array $options)
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
