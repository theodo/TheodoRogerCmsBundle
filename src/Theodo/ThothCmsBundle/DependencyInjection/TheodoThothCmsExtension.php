<?php

namespace Theodo\ThothCmsBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

class TheodoThothCmsExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        // Create a yaml file loader
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        
        // Load the services.yml file
        $loader->load('services.yml');
    }
}