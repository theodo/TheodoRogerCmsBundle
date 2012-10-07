<?php

namespace Theodo\RogerCmsBundle\Tests\Form\DataTransformer;

use Theodo\RogerCmsBundle\Form\DataTransformer\BlockTransformer;

class BlockTransformerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider concatenationDataProvider
     */
    public function testConcatenateBlocks($data, $expected)
    {
        $transformer = new BlockTransformer();
        $this->assertEquals($expected, $transformer->reverseTransform($data));
    }

    /**
     * @dataProvider concatenationDataProvider
     */
    public function testSplitBlocks($expected, $data)
    {
        $transformer = new BlockTransformer();
        $this->assertEquals($expected, $transformer->transform($data));
    }

    public function concatenationDataProvider()
    {
        return array(
            array(
                array('test' => 'Test 1', 'test2' => 'Test 2', 'test3' => 'Test 3'),
                <<<TWIG
{% block test %}Test 1{% endblock %}
{% block test2 %}Test 2{% endblock %}
{% block test3 %}Test 3{% endblock %}
TWIG
            ),
            array(null, null),
            array('test', 'test'),
        );
    }

}
