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
 * User repository test class.
 *
 * @author Vincent Guillon <vincentg@theodo.fr>
 * @author Benjamin Grandfond <benjaming@theodo.fr>
 */
namespace Theodo\RogerCmsBundle\Tests\Repository;

require_once __DIR__.'/../Test.php';

use Theodo\RogerCmsBundle\Tests\Test as TestCase;
use Theodo\RogerCmsBundle\Entity\User;
use Theodo\RogerCmsBundle\Repository\UserRepository;

class UserRepositoryTest extends TestCase
{
    public function setUp()
    {
        self::createRogerKernel();
    }

    /**
     * Test getAvailableLanguages function
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-28
     */
    public function testGetAvailableLanguages()
    {
        print_r("\n> Test \"getAvailableLanguages\" function");

        // Retrieve available languages
        $availableLanguages = UserRepository::getAvailableLanguages();

        // Test type of return
        $this->assertInternalType('array', $availableLanguages);

        // Test number of status
        $this->assertEquals(2, count($availableLanguages));
    }
}
