<?php

namespace Theodo\RogerCmsBundle\Tests\Form\Page;

// Used for < 2.3 compatibility
use Symfony\Component\Form\Tests\Extension\Core\Type\TypeTestCase;
use Theodo\RogerCmsBundle\Form\Page\LayoutType;

class LayoutTypeTest extends TypeTestCase
{
    public function testBind()
    {
        $form = $this->factory->create(new LayoutType());
        $data = array (
            'choice' => 'normal',
            'text' => '',
        );

        $form->bind($data);
        $this->assertTrue($form->isValid());
    }
}

