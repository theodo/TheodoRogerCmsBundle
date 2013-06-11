<?php

namespace Theodo\RogerCmsBundle\Tests\Extensions\Twig\Extension;

use Theodo\RogerCmsBundle\Extensions\Twig\Extension\RogerHelperExtension;

class RogerHelperExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testErrorInFields()
    {
        $extension = new RogerHelperExtension();
        $functions = $extension->getFunctions();
        $this->assertTrue(isset($functions['error_in_fields']));

        $values = array(
            'field_1' => 'test',
            'field_2' => 'test',
            'field_3' => 'test',
        );

        $goodField = $this->createFieldMock();
        $form = $this->createFieldMock(false, array('good' => $goodField));

        $this->assertFalse($extension->errorInFields($form, array('good')));
    }

    private function createFieldMock($errors = false, array $children = array())
    {
        $form = $this->getMock('Symfony\Component\Form\FormView', array(
            'offsetGet',
            'getChildren',
            'hasChildren',
            'getChild'
        ));

        $form->expects($this->any())
            ->method('hasChildren')
            ->will($this->returnValue(count($children) > 0));

        if (count($children) > 0) {
            foreach ($children as $child) {
                $child->expects($this->any())
                    ->method('getOffset')
                    ->with('parent')
                    ->will($this->returnValue($form));
            }

            $form->expects($this->any())
                ->method('getChildren')
                ->will($this->returnValue($children));

            $form->expects($this->any())
                ->method('getChild')
                ->will($this->returnCallback(
                    function ($key) use ($children) {
                        return $children[$key];
                    }
                ));
        }

        $form->expects($this->any())
            ->method('getOffset')
            ->with('parent')
            ->will($this->returnValue(null));

        $form->expects($this->any())
            ->method('getOffset')
            ->with('errors')
            ->will($this->returnValue($errors));

        return $form;
    }
}
