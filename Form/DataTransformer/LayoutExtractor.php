<?php

namespace Theodo\RogerCmsBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Theodo\RogerCmsBundle\Extractor\LayoutExtractorInterface;

/**
 * This DataTransformer splits page content on template and proper content
 *
 * @autor Marek Kalnik <marekk@theodo.fr>
 */
class LayoutExtractor implements DataTransformerInterface, LayoutExtractorInterface
{
    /**
     * Receives page content and splits it
     *
     * @param string $value
     *
     * @return array
     */
    public function transform($value)
    {
        if ($value === null) {
            return null;
        }

        if ($value) {
            if (!($layout = trim($this->matchLayoutName($value), '"'))) {
                $layout = trim($this->matchTemplateName($value), '"');
                $content = $this->removeLayoutFromContent($value, $layout);
            } else {
                $content = $this->removeLayoutFromContent($value, 'layout:'.$layout);
            }

            return array(
                'layout' => $layout,
                'content' => $content,
            );
        }

        return array(
            'layout' => '',
            'content' => '',
        );
    }


    /**
     * Parses form input to concatenate it into page content
     *
     * @param array $array
     *
     * @return mixed The value
     *
     * @throws UnexpectedTypeException if the given value is not an array
     * @throws TransformationFailedException if the given array can not be transformed
     */
    public function reverseTransform($array)
    {
        if (!is_array($array)) {
            throw new UnexpectedTypeException('An array should be passed as argument');
        }

        if (!array_key_exists('layout', $array) || !array_key_exists('content', $array)) {
            throw new TransformationFailedException('Array needs to contain content and layout');
        }

        $pageContent = $array['content'];

        if ($array['layout']) {
            $layout = explode(':', $array['layout']);
            if (count($layout) === 3) {
                $layoutPart = '{% extends \'' . $array['layout'] . '\' %}';
            } else {
                $layoutPart = '{% extends \'layout:' . $array['layout'] . '\' %}';
            }

            $pageContent = $layoutPart . $pageContent;
        }

        return $pageContent;
    }

    /**
     * Extracts layout from given twig template
     *
     * @param string $template
     */
    public function getLayout($template)
    {
        if ($layout = trim($this->matchLayoutName($template), '"')) {
            return 'layout:' . $layout;
        }

        $layout = trim($this->matchTemplateName($template), '"');

        return $layout;
    }

    /**
     * @param String $pageContent Content to find layout name in.
     *
     * @return String|Boolean Layout name or false if no layout
     */
    private function matchLayoutName($pageContent)
    {
        $layoutRegexp = '#{% extends [\',\"]layout:[\"]?(.*)[\"]?[\',\"] %}#';
        if (preg_match($layoutRegexp, $pageContent, $matches)) {
            return $matches[1];
        }

        return false;
    }

    /**
     * @param string $pageContent Contet to find the template name in.
     *
     * @return string|boolean Template name or false if no template
     */
    private function matchTemplateName($pageContent)
    {
        $layoutRegexp = '#{% extends [\',\"][\"]?(.*)[\"]?[\',\"] %}#';
        if (preg_match($layoutRegexp, $pageContent, $matches)) {
            return $matches[1];
        }

        return false;
    }

    private function removeLayoutFromContent($pageContent, $layout)
    {
        if (strpos($pageContent, "{% extends") === 0) {
            return preg_replace("/{% extends '" . $layout . "' %}/", '', $pageContent);
        }

        return $pageContent;
    }
}
