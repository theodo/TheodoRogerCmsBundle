<?php

namespace Theodo\RogerCmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Theodo\RogerCmsBundle\Entity\Snippet;

class SnippetType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', 'hidden');
        $builder->add('name', 'text', array('required' => true));
        $builder->add('content', 'textarea', array('required' => false));
        $builder->add('cacheable', 'checkbox', array('required' => false));
        $builder->add('public', 'checkbox', array('required' => false));
        $builder->add('lifetime', 'text', array('required' => false));
    }

    public function getDefaultOptions()
    {
        return array(
            'data_class' => 'Theodo\RogerCmsBundle\Entity\Snippet',
        );
    }

    public function getName()
    {
        return 'snippet';
    }
}
