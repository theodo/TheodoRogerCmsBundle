<?php

namespace Theodo\RogerCmsBundle\Tests\Form\DataTransformer;

use Theodo\RogerCmsBundle\Form\DataTransformer\LayoutExtractor;
use Theodo\RogerCmsBundle\Tests\Test as TestCase;

/**
 * Tests the layout extractor
 *
 * @author Marek Kalnik <marekk@theodo.fr>
 */
class LayoutExtractorTest extends TestCase
{
    /**
     * @dataProvider getContentData
     */
    public function testExtractLayoutFromContent($data, $layout, $content)
    {
        $extractor = new LayoutExtractor();
        $analysedData = $extractor->transform($data);

        $this->assertEquals($layout, $analysedData['layout']);
        $this->assertEquals($content, $analysedData['content']);
    }

    /**
     * @dataProvider getContentData
     */
    public function testConcatenateContent($data, $layout, $content)
    {
        $extractor = new LayoutExtractor();
        $analysedData = $extractor->reverseTransform(array('layout' => $layout, 'content' => $content));

        $this->assertEquals($data, $analysedData);
    }

    public function getContentData()
    {
        return array(
            array(
<<<TWIG
{% extends 'layout:normal' %}Test text
TWIG
            , 'normal', 'Test text'
            ),
            array(
<<<TWIG2
{% extends 'AcmeDemoBundle:Default:index.html.twig' %}Test text
TWIG2
            , 'AcmeDemoBundle:Default:index.html.twig', 'Test text'
            )
        );
    }
}
