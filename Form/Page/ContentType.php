<?php

namespace Theodo\RogerCmsBundle\Form\Page;

use Theodo\RogerCmsBundle\Repository\ContentRepositoryInterface;
use Theodo\RogerCmsBundle\Form\DataTransformer\BlockTransformer;
use Theodo\RogerCmsBundle\Form\DataTransformer\LayoutExtractor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ContentType extends AbstractType
{
    private $repository;

    public function __construct(ContentRepositoryInterface $layoutRepository)
    {
        $this->repository = $layoutRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', 'textarea', array(
                'required' => false,
                'error_bubbling' => true,
            ))
            ->add('layout', 'roger_cms_page_layout', array(
                'choices' => $this->getLayoutNames(),
                'error_bubbling' => true,
            ))
        ;

        $builder->get('content')
            ->appendClientTransformer(new BlockTransformer());
        $builder
            ->appendClientTransformer(new LayoutExtractor())
        ;
    }

    public function getParent()
    {
        return 'form';
    }

    public function getName()
    {
        return 'roger_cms_page_content';
    }

    private function getLayoutNames()
    {
        $layouts = $this->repository->findAll('layout');

        if (count($layouts) == 0) {
            return array();
        }

        $layoutNames = array_map(function($layout) {
            return $layout->getName();
        }, $layouts);

        return array_combine(array_values($layoutNames), $layoutNames);
    }
}
