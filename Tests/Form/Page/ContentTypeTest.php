<?php

namespace Theodo\RogerCmsBundle\Tests\Form\Page;

use Symfony\Component\Form\Tests\Extension\Core\Type\TypeTestCase;
use Theodo\RogerCmsBundle\Form\Page\ContentType;
use Theodo\RogerCmsBundle\Form\Page\LayoutType;

class ContentTypeTests extends TypeTestCase
{
    public function testBind()
    {
        $this->factory->addType(new LayoutType());

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

        $form = $this->factory->create(new ContentType($contentRepository));
        $data = array(
            'content' => 
            array (
                'content' => '<div id="theodo"><h2>Theodo</h2></div>',
                'footer' => 'Copyright Theodo 2011',
            ),
            'layout' => 
                array (
                    'choice' => 'normal',
                    'text' => '',
                ),
            );

        $form->bind($data);
        $this->assertTrue($form->isValid());
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
