<?php

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    'Symfony'          => array(__DIR__.'/../vendor/symfony/src', __DIR__.'/../vendor/bundles'),
    'Doctrine\\Common' => __DIR__.'/../vendor/doctrine-common/lib',
    'Doctrine\\DBAL'   => __DIR__.'/../vendor/doctrine-dbal/lib',
    'Doctrine'         => __DIR__.'/../vendor/doctrine/lib',
    'Doctrine\\Common\\DataFixtures'   => __DIR__.'/../vendor/doctrine-fixtures/lib',
    'Stof'             => __DIR__.'/../vendor/bundles',
    'Gedmo'            => __DIR__.'/../vendor/gedmo-doctrine-extensions/lib',
));
$loader->registerPrefixes(array(
    'Twig_'            => __DIR__.'/../vendor/twig/lib',
));
$loader->register();

/**
 * Load TheodoRogerCmsBundle classes
 */
spl_autoload_register(function($class) {
    if (0 === strpos($class, 'Theodo\\RogerCmsBundle\\')) {
        $path = __DIR__.'/../../../'.implode('/', array_slice(explode('\\', $class), 2)).'.php';
        if (!stream_resolve_include_path($path)) {
            return false;
        }
        require_once $path;
        return true;
    }
});
