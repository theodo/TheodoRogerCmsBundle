<?php

namespace Theodo\RogerCmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Theodo\RogerCmsBundle\Entity\Page;
use Theodo\RogerCmsBundle\Repository\PageRepository;

class PageType extends AbstractType
{
    /**
     * Form builder
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-21
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Set inputs
        $builder->add('parentId', 'hidden', array('required' => true));
        $builder->add('name', 'text', array('required' => true));
        $builder->add('slug', 'text', array('required' => true));
        $builder->add('breadcrumb', 'text', array('required' => false));
        $builder->add('title', 'text', array('required' => false));
        $builder->add('description', 'text', array('required' => false));
        $builder->add('keywords', 'text', array('required' => false));
        $builder->add('content', 'roger_cms_page_content', array('required' => false));
        $builder->add('contentType', 'choice', array(
            'choices'   => PageRepository::getAvailableContentTypes(),
            'required'  => true
        ));
        $builder->add('cacheable', 'checkbox', array('required' => false));
        $builder->add('public', 'checkbox', array('required' => false));
        $builder->add('lifetime', 'text', array('required' => false));
    }

    /**
     * @inheritdoc
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Theodo\RogerCmsBundle\Entity\Page',
        ));
    }

    /**
     * @return string Form type name
     */
    public function getName()
    {
        return 'page';
    }
}
