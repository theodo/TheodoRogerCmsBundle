<?php

/*
 * This file is part of the Roger CMS Bundle
 *
 * (c) Theodo <contact@theodo.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Theodo\RogerCmsBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Adds tagged twig.extension services to the roger_cms twig service
 *
 * @author Mathieu Dähne <mathieud@theodo.fr>
 */
class RogerTwigEnvironmentPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('roger.twig')) {
            return;
        }

        $definition = $container->getDefinition('roger.twig');

        /*
         * Extensions must always be registered before everything else.
         * For instance, global variable definitions must be registered
         * afterward. If not, the globals from the extensions will never
         * be registered.
         */
        $calls = $definition->getMethodCalls();
        $definition->setMethodCalls(array());
        // TODO: filter usefull extensions ?
        foreach ($container->findTaggedServiceIds('twig.extension') as $id => $attributes) {
            $definition->addMethodCall('addExtension', array(new Reference($id)));
        }
        foreach ($container->findTaggedServiceIds('theodo_roger_cms.twig.extension') as $id => $attributes) {
            $definition->addMethodCall('addExtension', array(new Reference($id)));
        }
        $definition->setMethodCalls(array_merge($definition->getMethodCalls(), $calls));
    }
}
