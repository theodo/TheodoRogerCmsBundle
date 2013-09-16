<?php

/*
 * This file is part of the Roger CMS Bundle
 *
 * (c) Theodo <contact@theodo.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Theodo\RogerCmsBundle\Extensions\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Controller\ControllerReference;
use Theodo\RogerCmsBundle\Extensions\Twig\TokenParser\SnippetTokenParser;

/**
 * This Twig extension adds snippet rendering functions
 */
class RogerActionsExtension extends \Twig_Extension
{
    private $container;

    /**
     * Constructor.
     *
     * @param ContainerInterface $container The service container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Returns the Response content for a given controller or URI.
     *
     * @param string $name       The name of the snippet or its controller
     * @param array  $attributes An array of request attributes
     *
     * @return string The response content
     *
     * @see Symfony\Bundle\FrameworkBundle\Controller\ControllerResolver::render()
     */
    public function renderSnippet($name, $attributes = array())
    {
        if (strpos($name, ':') !== false) {
            $controller = $name;
            $array = explode(':', $name);
            $name = end($array);
        } else {
            $controller = 'TheodoRogerCmsBundle:Frontend\Frontend:snippet';
        }

        $params['name'] = $name;
        $params['attributes'] = $attributes;
        $options = array();

        if ($this->container->has('fragment.renderer.esi')) {
            $options['standalone'] = true;
        }

        return $this->container
            ->get('templating.helper.actions')
            ->render(new ControllerReference($controller, $params), $options);
    }

    /**
     * Returns the token parser instance to add to the existing list.
     *
     * @return array An array of Twig_TokenParser instances
     */
    public function getTokenParsers()
    {
        return array(
            new SnippetTokenParser(),
        );
    }

    /**
     * Gets extension name
     *
     * @return string
     */
    public function getName()
    {
        return 'roger_actions';
    }
}
