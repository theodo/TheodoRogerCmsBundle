<?php

namespace Theodo\RogerCmsBundle\Tests\Form\Page;

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

    /**
array (
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
    'content' => 
    array (
      'content' => '<div id="theodo">

    <h2>

        Theodo</h2>

</div>

',
      'footer' => 'Copyright Theodo 2011

',
    ),
    'layout' => 
    array (
      'choice' => 'normal',
      'text' => '',
    ),
  ),
  '_token' => '5d2dabe9582589c269ae0dc4f56fd938d476eb05',
  'parentId' => '1',
)
     */
}

