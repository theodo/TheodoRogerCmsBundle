<?php

namespace Theodo\RogerCmsBundle\Tests\Form;

use Symfony\Component\Form\PreloadedExtension;
use Theodo\RogerCmsBundle\Form\Page\LayoutType;
use Theodo\RogerCmsBundle\Form\Page\ContentType;
use Theodo\RogerCmsBundle\Form\PageType;
use Phake;

// Used for < 2.3 compatibility
use Symfony\Component\Form\Tests\Extension\Core\Type\TypeTestCase;

class PageTypeTest extends TypeTestCase
{
    private $cr;

    public function setUp()
    {
        $this->cr = Phake::mock('Theodo\RogerCmsBundle\Repository\ContentRepositoryInterface');

        parent::setUp();
    }
    public function testBind()
    {
        $layout = new Layout();
        $layout->name = 'normal';

        $layout2 = new Layout();
        $layout2->name = 'normaljs';

        Phake::when($this->cr)
            ->findAll('layout')
            ->thenReturn(array($layout, $layout2));

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
            'content' => array (
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

    protected function getExtensions()
    {
        $layoutType = new LayoutType();
        $contentType = new ContentType($this->cr);

        return array(new PreloadedExtension(array(
            $layoutType->getName() => $layoutType,
            $contentType->getName() => $contentType,
        ), array()));
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
