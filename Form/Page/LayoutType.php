<?php

namespace Theodo\RogerCmsBundle\Form\Page;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Theodo\RogerCmsBundle\Form\DataTransformer\ChoiceWithTextInputTransformer;

/**
 * This class defines form type for page layout selection,
 * providing an option to either choose from existing layouts
 * or enter the name manually (for file layouts).
 */
class LayoutType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('choice', 'choice', array(
                'choices'   => $options['choices'],
                'multiple'  => false,
                'expanded'  => false,
                'label'     => 'Statut',
                'required'  => false,
                'error_bubbling' => true,
            ))
            ->add('text', 'text', array(
                'required' => false,
                'label' => '',
                'error_bubbling' => true,
            ))
        ;

        $builder
            ->addViewTransformer(new ChoiceWithTextInputTransformer($options['choices']))
       ;
    }


    /**
     * @inheritdoc
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => array()
        ));
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'roger_cms_page_layout';
    }

    /**
     * @inheritdoc
     */
    public function getParent()
    {
        return 'form';
    }
}
