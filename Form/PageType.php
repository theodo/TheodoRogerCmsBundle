<?php

namespace Theodo\RogerCmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

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
        $builder->add('content', 'textarea', array('required' => false));
        $builder->add('status', 'choice', array(
            'choices'   => PageRepository::getAvailableStatus(),
            'required'  => true
        ));
        $builder->add('contentType', 'choice', array(
            'choices'   => PageRepository::getAvailableContentTypes(),
            'required'  => true
        ));
        $builder->add('cacheable', 'checkbox', array('required' => false));
        $builder->add('public', 'checkbox', array('required' => false));
        $builder->add('lifetime', 'text', array('required' => false));

        // Display publishedAt date only in edition
        if (null !== $options['data']->getId()) {
            $builder->add('publishedAt', 'date', array('required' => false));
        }
    }

    /**
     * Form default options
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-21
     */
    public function getDefaultOptions()
    {
        return array(
           'data_class' => 'Theodo\RogerCmsBundle\Entity\Page',
        );
    }

    /**
     * @return string Form type name
     */
    public function getName()
    {
        return 'page';
    }
}
