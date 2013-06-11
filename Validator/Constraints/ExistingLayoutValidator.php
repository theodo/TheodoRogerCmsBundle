<?php

namespace Theodo\RogerCmsBundle\Validator\Constraints;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

use Theodo\RogerCmsBundle\Extractor\LayoutExtractorInterface;

/**
 * @todo This validator should we able to work on content as it is used on the object rathern 
 * that the form. Maybe the Content property should be replaced by an object, to do it in 
 * a more elegant way?
 *
 * @author Marek Kalnik <marekk@theodo.fr>
 */
class ExistingLayoutValidator extends ConstraintValidator
{
    private $loader;

    private $extractor;

    /**
     * @param \Twig_LoaderInterface $load The loader used to find the template
     * @param LayoutExtractor $extractor Used to extract the template name form template
     */
    public function __construct(\Twig_LoaderInterface $loader, LayoutExtractorInterface $extractor)
    {
        $this->loader = $loader;
        $this->extractor = $extractor;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($content, Constraint $constraint)
    {
        $layout = $this->extractor->getLayout($content);

        if ($layout) {
            try {
                $this->loader->getSource($layout);
            } catch (\Twig_Error_Loader $e) {
                $this->context->addViolation('Template not found.');
            }
        }
    }
}
