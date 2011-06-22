<?php

namespace Sadiant\CmsBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

class CmsServicesExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        // Create a yaml file loader
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        
        // Load the services.yml file
        $loader->load('services.yml');
    }

    public function getAlias()
    {
        return 'cms_services';
    }
}