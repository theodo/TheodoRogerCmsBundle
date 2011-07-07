<?php

namespace Theodo\ThothCmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

use Theodo\ThothCmsBundle\Entity\Snippet;

class SnippetType extends AbstractType
{

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('id', 'hidden');
        $builder->add('name', 'text', array('required' => true));
        $builder->add('content', 'textarea', array('required' => false));
        $builder->add('cacheable', 'checkbox', array('required' => false));
        $builder->add('public', 'checkbox', array('required' => false));
        $builder->add('lifetime', 'text', array('required' => false));
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Theodo\ThothCmsBundle\Entity\Snippet',
        );
    }

}