<?php

namespace Theodo\RogerCmsBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class BlockTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        if (!$value) {
            return $value;
        }

        if (!preg_match_all('#{% block (?P<block_name>(.*)) %}(?P<block_content>(.*)){% endblock %}#sU', $value, $matches)) {

            return $value;
        }

        return array_combine($matches['block_name'], $matches['block_content']);
    }

    public function reverseTransform($value)
    {
        if (!$value || !is_array($value)) {
            return $value;
        }

        $text = '';

        foreach ($value as $blockName => $blockContent) {
            $text .= '{% block ' . $blockName . ' %}' . $blockContent . '{% endblock %}' . "\n";
        }

        return rtrim($text, "\n");
    }
}
