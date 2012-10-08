<?php

namespace Theodo\RogerCmsBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

/**
 * This data transformer allows having a choice field and a text filed
 * thus allowing user to insert any value not existing in the predefined choices.
 *
 * @autor Marek Kalnik <marekk@theodo.fr>
 */
class ChoiceWithTextInputTransformer implements DataTransformerInterface
{
    private $choices = array();

    /**
     * @param array $choices
     */
    public function __construct(array $choices)
    {
        $this->choices = $choices;
    }

    /**
     * Receives data from model and fills either choices or input text field.
     *
     * @param string $value
     *
     * @return array
     */
    public function transform($value)
    {
        $result = array(
            'choice' => '',
            'text' => ''
        );

        if (in_array($value, $this->choices)) {
            $result['choice'] = $value;
        } elseif ($value != null) {
            $result['text'] = $value;
        }

        return $result;
    }

    /**
     * Parses choices and text input and saves the result as string.
     * The text, if exists has priority over choices.
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
            throw new UnexpectedTypeException($array, 'array');
        }

        if (!empty($array['text']) && !isset($array['choice'])) {
            throw new TransformationFailedException('The data needs to contain "text" and "choice" fields');
        }

        if (!empty($array['text'])) {
            return $array['text'];
        }

        return $array['choice'];
    }
}
