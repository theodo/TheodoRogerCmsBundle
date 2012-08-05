<?php

namespace Theodo\RogerCmsBundle\Form\Page;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

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
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('choice', 'choice', array(
            'choices'   => $options['choices'],
            'multiple'  => false,
            'expanded'  => true,
            'label'     => 'Statut',
            'required'  => false,
        ));

        $builder->add('text', 'text', array(
            'required' => false,
            'label' => '',
        ));

        $builder
            ->appendClientTransformer(new CadioWithTextInputTransformer($options['choices']))
        ;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'roger_cms_page_layout';
    }
}
