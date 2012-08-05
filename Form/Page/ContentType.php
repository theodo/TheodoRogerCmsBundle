<?php

namespace Theodo\RogerCmsBundle\Form\Page;

use Symfony\Component\Form\AbstractType;

class ContentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'content', 'textarea', array('required' => false),
            'layout', 'roger_cms_page_layout', array(
                'choices'   => PageRepository::getAvailableContentTypes(),
            ),
        )
    }

    public function getPaternt(array $options)
    {
        return 'form';
    }

    public function getName()
    {
        return 'roger_cms_page_content';
    }
}
