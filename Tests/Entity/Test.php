<?php
/*
 * This file is part of the Roger CMS Bundle
 *
 * (c) Theodo <contact@theodo.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * Entities base test class.
 *
 * @author Benjamin Grandfond <benjaming@theodo.fr>
 */

namespace Theodo\RogerCmsBundle\Tests\Entity;

use Theodo\RogerCmsBundle\Tests\Test as BaseTestCase;

abstract class Test extends BaseTestCase
{
    public function setUp()
    {
        self::createRogerKernel();

        // Load "test" entity manager
        static::$em = static::$kernel->getContainer()->get('doctrine')->getEntityManager();
    }
}
