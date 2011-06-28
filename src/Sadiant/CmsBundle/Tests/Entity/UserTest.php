<?php

require_once __DIR__ . '/../../../../../app/AppKernel.php';

use Sadiant\CmsBundle\Entity\User;
use Sadiant\CmsBundle\Tests\Unit;
use Doctrine\Common\DataFixtures\Loader;
use Sadiant\CmsBundle\DataFixtures\ORM\UserData;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\ORM\Query;

class UserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    public function setUp()
    {
        // Load and boot kernel
        $kernel = new \AppKernel('test', true);
        $kernel->boot();

        // Load "test" entity manager
        $this->em = $kernel->getContainer()->get('doctrine')->getEntityManager('test');
    }

    /**
     * EntityManager getter
     *
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEntityManager()
    {
        return $this->em;
    }

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
        $user = $em->getRepository('SadiantCmsBundle:User')->findOneBy(array('username' => 'admin'));

        // Test user
        $this->assertInstanceOf('Sadiant\CmsBundle\Entity\User', $user);
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
        $user = $em->getRepository('SadiantCmsBundle:User')->findOneBy(array('username' => 'admin'));

        // Test user
        $this->assertInstanceOf('Sadiant\CmsBundle\Entity\User', $user);
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
        $user = $em->getRepository('SadiantCmsBundle:User')->findOneBy(array('username' => 'admin'));

        // Test user
        $this->assertInstanceOf('Sadiant\CmsBundle\Entity\User', $user);
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
        $user = $em->getRepository('SadiantCmsBundle:User')->findOneBy(array('username' => 'admin'));

        // Test user
        $this->assertInstanceOf('Sadiant\CmsBundle\Entity\User', $user);
        $this->assertEquals('Theodore De Banville', $user->getName());

        // Test equals
        $this->assertEquals(md5(strtolower(trim($user->getEmail()))), $user->getGravatarEmailHash());
    }
}
