<?php
/*
 * This file is part of the Roger CMS Bundle
 *
 * (c) Theodo <contact@theodo.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 *
 * Idea from http://www.willdurand.fr/tests-unitaires-et-fonctionnels-sur-un-bundle-en-symfony2/
 */

require_once __DIR__.'/../Fixtures/Symfony/app/bootstrap.php.cache';

$filesystem = new \Symfony\Component\HttpKernel\Util\Filesystem();
$filesystem->remove(__DIR__.'/../Fixtures/Symfony/app/cache');
$filesystem->remove(__DIR__.'/../Fixtures/Symfony/app/logs');
$filesystem->mkdir(__DIR__.'/../Fixtures/Symfony/app/cache');
$filesystem->mkdir(__DIR__.'/../Fixtures/Symfony/app/logs');
