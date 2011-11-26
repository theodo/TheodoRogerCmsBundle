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
 * User entity test class.
 *
 * @author Vincent Guillon <vincentg@theodo.fr>
 * @author Benjamin Grandfond <benjaming@theodo.fr>
 */
namespace Theodo\RogerCmsBundle\Tests;

require_once __DIR__.'/Test.php';

use Theodo\RogerCmsBundle\Tests\Entity\Test as TestCase;
use Theodo\RogerCmsBundle\Entity\User;

class UserTest extends TestCase
{
    /**
     * Test getRoles function
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-28
     */
    public function testGetRoles()
    {
        print_r("\n> Test \"getRoles\" function");

        // Retrieve entity manager
        $em = $this->getEntityManager();

        // Retrieve "about" page
        $user = $em->getRepository('TheodoRogerCmsBundle:User')->findOneBy(array('username' => 'admin'));

        // Test user
        $this->assertInstanceOf('Theodo\RogerCmsBundle\Entity\User', $user);
        $this->assertEquals('Theodore De Banville', $user->getName());

        // Test getRoles
        $roles = $user->getRoles();
        $this->assertEquals(1, count($roles));
    }

    /**
     * Test getUserRoles function
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-28
     */
    public function testGetUserRoles()
    {
        print_r("\n> Test \"getUserRoles\" function");

        // Retrieve entity manager
        $em = $this->getEntityManager();

        // Retrieve "about" page
        $user = $em->getRepository('TheodoRogerCmsBundle:User')->findOneBy(array('username' => 'admin'));

        // Test user
        $this->assertInstanceOf('Theodo\RogerCmsBundle\Entity\User', $user);
        $this->assertEquals('Theodore De Banville', $user->getName());

        // Test getRoles
        $roles = $user->getUserRoles();
        $this->assertEquals(1, count($roles));
    }

    /**
     * Test equals function
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-28
     */
    public function testEquals()
    {
        print_r("\n> Test \"equals\" function");

        // Retrieve entity manager
        $em = $this->getEntityManager();

        // Retrieve "about" page
        $user = $em->getRepository('TheodoRogerCmsBundle:User')->findOneBy(array('username' => 'admin'));

        // Test user
        $this->assertInstanceOf('Theodo\RogerCmsBundle\Entity\User', $user);
        $this->assertEquals('Theodore De Banville', $user->getName());

        // Test equals
        $this->assertTrue($user->equals($user));
    }

    /**
     * Test getGravatarEmailHash function
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-28
     */
    public function testGetGravatarEmailHash()
    {
        print_r("\n> Test \"getGravatarEmailHash\" function");

        // Retrieve entity manager
        $em = $this->getEntityManager();

        // Retrieve "about" page
        $user = $em->getRepository('TheodoRogerCmsBundle:User')->findOneBy(array('username' => 'admin'));

        // Test user
        $this->assertInstanceOf('Theodo\RogerCmsBundle\Entity\User', $user);
        $this->assertEquals('Theodore De Banville', $user->getName());

        // Test equals
        $this->assertEquals(md5(strtolower(trim($user->getEmail()))), $user->getGravatarEmailHash());
    }
}
