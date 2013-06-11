<?php

namespace Theodo\RogerCmsBundle\Tests\Form;

use Symfony\Component\Form\Tests\Extension\Core\Type\TypeTestCase;
use Theodo\RogerCmsBundle\Form\Page\LayoutType;
use Theodo\RogerCmsBundle\Form\Page\ContentType;
use Theodo\RogerCmsBundle\Form\PageType;

class PageTypeTest extends TypeTestCase
{
    public function testBind()
    {
        $layout = new Layout();
        $layout->name = 'normal';

        $layout2 = new Layout();
        $layout2->name = 'normaljs';

        $contentRepository = $this->getMock(
            'Theodo\RogerCmsBundle\Repository\ContentRepositoryInterface'
        );

        $contentRepository->expects($this->once())
            ->method('findAll')
            ->with('layout')
            ->will($this->returnValue(array($layout, $layout2)));
        $this->factory->addType(new LayoutType());
        $this->factory->addType(new ContentType($contentRepository));

        $form = $this->factory->create(new PageType());
        $form->bind($this->getData());
        $this->assertTrue($form->isValid());
    }

    public function getData()
    {
        return array (
            'name' => 'Theodo',
            'slug' => 'theodo',
            'breadcrumb' => 'Theodo',
            'description' => 'Theodo page',
            'title' => '',
            'keywords' => '',
            'contentType' => 'text/html',
            'cacheable' => '1',
            'lifetime' => '',
            'content' => 
            array (
                'content' => array (
                    'content' => '<div id="theodo"><h2>Theodo</h2></div>',
                    'footer' => 'Copyright Theodo 2011',
                ),
                'layout' => array (
                    'choice' => 'normal',
                    'text' => '',
                ),
            ),
            '_token' => '5d2dabe9582589c269ae0dc4f56fd938d476eb05',
            'parentId' => '1',
        );
    }
}

class Layout
{
    public $name;

    public function getName()
    {
        return $this->name;
    }
}
