<?php
/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Updated fot the TheodoRogerCmsBundle needs by Benjamin Grandfond <benjaming@theodo.fr>
 */

$vendorDir = __DIR__.'/../vendor';
require_once $vendorDir.'/symfony/src/Symfony/Component/ClassLoader/UniversalClassLoader.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;
use Doctrine\Common\Annotations\AnnotationRegistry;

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    'Symfony'          => array($vendorDir.'/symfony/src', $vendorDir.'/bundles'),
    'Doctrine\\Common' => $vendorDir.'/doctrine-common/lib',
    'Doctrine\\DBAL'   => $vendorDir.'/doctrine-dbal/lib',
    'Doctrine'         => $vendorDir.'/doctrine/lib',
    'Stof'             => $vendorDir.'/bundles',
    'Gedmo'            => $vendorDir.'/gedmo-doctrine-extensions/lib',
    'Theodo'           => __DIR__.'/../',
));
$loader->registerPrefixes(array(
    'Twig_Extensions_' => $vendorDir.'/twig-extensions/lib',
    'Twig_'            => $vendorDir.'/twig/lib',
));
