<?php

namespace Theodo\RogerCmsBundle\Extractor;

/**
 * Implement this interface to extract layout string from a template.
 * This can be useful if you have other Twig extensions that parse layouts.
 *
 * @author Marek Kalnik <marekk@theodo.fr>
 */
interface LayoutExtractorInterface
{
    /**
     * Function extracting the layout from template.
     * If no layout found this should return a empty string.
     *
     * @param string $template
     *
     * @return string
     */
    public function getLayout($template);
}
