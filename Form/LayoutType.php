<?php

namespace Theodo\RogerCmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Theodo\RogerCmsBundle\Entity\Layout;

class LayoutType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', 'hidden');
        $builder->add('name', 'text', array('required' => true));
        $builder->add('content', 'textarea', array('required' => false));
    }

    /**
     * @inheritdoc
     */
    public function getDefaultOptions()
    {
        return array(
            'data_class' => 'Theodo\RogerCmsBundle\Entity\Layout',
        );
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'roger_cms_page_layout';
    }
}
