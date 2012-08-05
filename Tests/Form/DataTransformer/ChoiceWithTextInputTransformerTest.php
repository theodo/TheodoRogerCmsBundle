<?php

namespace Theodo\RogerCmsBundle\Tests\Form\DataTransformer;

use Theodo\RogerCmsBundle\Form\DataTransformer\ChoiceWithTextInputTransformer as Transformer;
use Theodo\RogerCmsBundle\Tests\Test as TestCase;

/**
 * Unit test for ChoiceWithTextInputTransformer class
 */
class ChoiceWithTextInputTransformerTest extends TestCase
{
    /**
     * Tests transforming text from model to from
     */
    public function testTransform()
    {
        $transformer = new Transformer($this->getChoices());

        $expected = array(
            'choice' => 'Choice 1',
            'text' => '',
        );
        $this->assertEquals($expected, $transformer->transform('Choice 1'));

        $expected = array(
            'choice' => '',
            'text' => 'Test',
        );
        $this->assertEquals($expected, $transformer->transform('Test'));
    }

    /**
     * Test transforming form values into single string
     */
    public function testReverseTransform()
    {
        $transformer = new Transformer($this->getChoices());

        $values = array(
            'choice' => 'Choice 1',
            'text' => '',
        );
        $this->assertEquals('Choice 1', $transformer->reverseTransform($values));

        $values = array(
            'choice' => 'Choice 1',
            'text' => 'Test',
        );
        $this->assertEquals('Test', $transformer->reverseTransform($values));
    }

    /**
     * Choice fixtures
     *
     * @return array
     */
    private function getChoices()
    {
        return array(
            'Choice 1',
            'Choice 2',
            'Choice 3',
        );
    }
}
