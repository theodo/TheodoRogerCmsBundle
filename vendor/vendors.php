#!/usr/bin/env php
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

set_time_limit(0);

$vendorDir = __DIR__;
$deps = array(
    array('symfony', 'http://github.com/symfony/symfony', isset($_SERVER['SYMFONY_VERSION']) ? $_SERVER['SYMFONY_VERSION'] : 'origin/master'),
    array('doctrine', 'http://github.com/doctrine/doctrine2', 'origin/master'),
    array('doctrine-common', 'http://github.com/doctrine/common', 'origin/master'),
    array('doctrine-dbal', 'http://github.com/doctrine/dbal', 'origin/master'),
    array('twig', 'https://github.com/fabpot/Twig', 'origin/master'),
    array('bundles/Stof/DoctrineExtensionsBundle', 'https://github.com/stof/StofDoctrineExtensionsBundle.git', 'origin/master'),
    array('gedmo-doctrine-extensions', 'https://github.com/l3pp4rd/DoctrineExtensions.git', 'origin/master'),
);

foreach ($deps as $dep) {
    list($name, $url, $rev) = $dep;

    echo "> Installing/Updating $name\n";

    $installDir = $vendorDir.'/'.$name;
    if (!is_dir($installDir)) {
        system(sprintf('git clone -q %s %s', escapeshellarg($url), escapeshellarg($installDir)));
    }

    system(sprintf('cd %s && git fetch -q origin && git reset --hard %s', escapeshellarg($installDir), escapeshellarg($rev)));
}
