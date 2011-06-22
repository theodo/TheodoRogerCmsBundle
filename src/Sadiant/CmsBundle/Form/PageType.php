<?php

namespace Sadiant\CmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

use Sadiant\CmsBundle\Entity\Page;
use Sadiant\CmsBundle\Repository\PageRepository;

class PageType extends AbstractType
{
    protected $em;

    public function __construct($em)
    {
        $this->em = $em;
    }
    
    public function buildForm(FormBuilder $builder, array $options)
    {   
        $builder->add('parent_id', 'hidden', array('required' => true));
        $builder->add('name', 'text', array('required' => true));
        $builder->add('slug', 'text', array('required' => true));
        $builder->add('breadcrumb', 'text', array('required' => false));
        $builder->add('description', 'text', array('required' => false));
        $builder->add('content', 'textarea', array('required' => true));
        $builder->add('status', 'choice', array(
            'choices'   => $this->em->getRepository('SadiantCmsBundle:Page')->getAvailableStatus(),
            'required'  => true
        ));
        
        // @TODO à afficher uniquement si "edit"
        $builder->add('published_at', 'date', array('required' => false));
    }
   
    public function getDefaultOptions(array $options)
    {
        return array(
           'data_class' => 'Sadiant\CmsBundle\Entity\Page',
        );
    }
}