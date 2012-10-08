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
     *
     * @dataProvider getTestData
     */
    public function testTransform($input, $expected)
    {
        $transformer = new Transformer($this->getChoices());

        $this->assertEquals($expected, $transformer->transform($input));
    }

    /**
     * Test transforming form values into single string
     *
     * @dataProvider getTestData
     */
    public function testReverseTransform($expected, $input)
    {
        $transformer = new Transformer($this->getChoices());

        $this->assertEquals($expected, $transformer->reverseTransform($input));
    }

    public function getTestData()
    {
        return array(
            array('Choice 1', array('choice' => 'Choice 1', 'text' => '')),
            array('Test', array('choice' => '', 'text' => 'Test')),
        );
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
