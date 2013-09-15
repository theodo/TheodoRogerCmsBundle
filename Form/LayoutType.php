<?php

namespace Theodo\RogerCmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
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
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Theodo\RogerCmsBundle\Entity\Layout',
        ));
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'layout';
    }
}
