<?php

/*
 * This file is part of the Roger CMS Bundle
 *
 * (c) Theodo <contact@theodo.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Theodo\RogerCmsBundle\Extensions\Twig\Node;

/**
 * Represents a snippet node.
 *
 * @author Mathieu Dähne <mathieud@theodo.fr>
 */
class SnippetNode extends \Twig_Node
{
    public function __construct(\Twig_Node_Expression $name, \Twig_Node_Expression $attributes, $lineno, $tag = null)
    {
        parent::__construct(array('name' => $name, 'attributes' => $attributes), array(), $lineno, $tag);
    }

    /**
     * Compiles the node to PHP.
     *
     * @param \Twig_Compiler $compiler A Twig_Compiler instance
     */
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler
            ->addDebugInfo($this)
            ->write("echo \$this->env->getExtension('roger_actions')->renderSnippet(")
            ->subcompile($this->getNode('name'))
            ->raw(', ')
            ->subcompile($this->getNode('attributes'))
            ->raw(");\n")
        ;
    }
}
