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
    public function testExtractLayoutFromContent()
    {
        $data = <<<TWIG
{% extends 'layout:normal' %}
Test text
TWIG;

        $extractor = new LayoutExtractor();
        $analysedData = $extractor->transform($data);

        $this->assertEquals('normal', $analysedData['layout']);
        $this->assertEquals('Test text', $analysedData['content']);
    } 

    public function testConcatenateContent()
    {
        $layout = 'normal';
        $content = 'Test text';

        $extractor = new LayoutExtractor();
        $analysedData = $extractor->reverseTransform(array('layout' => $layout, 'content' => $content));

        $data = <<<TWIG
{% extends 'layout:normal' %}Test text
TWIG;

        $this->assertEquals($data, $analysedData);
    } 
}
