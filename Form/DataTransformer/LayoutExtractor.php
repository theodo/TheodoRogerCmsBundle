<?php

namespace Theodo\RogerCmsBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

/**
 * This DataTransformer splits page content on template and proper content
 *
 * @autor Marek Kalnik <marekk@theodo.fr>
 */
class LayoutExtractor implements DataTransformerInterface
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
            return array(
                'layout' => trim($this->matchLayoutName($value), '"'),
                'content' => $this->removeLayoutFromContent($value),
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
            $pageContent = '{% extends \'layout:' . $array['layout'] . '\' %}' . $pageContent;
        }

        return $pageContent;
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

    private function removeLayoutFromContent($pageContent)
    {
        if (strpos($pageContent, "{% extends 'layout") === 0) {
            return preg_replace("/{% extends 'layout:(.*)' %}\s+/", '', $pageContent);
        }

        return $pageContent;
    }
}
