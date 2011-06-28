<?php

require_once __DIR__.'/../../../../../app/AppKernel.php';

use Theodo\ThothCmsBundle\Entity\User;
use Theodo\ThothCmsBundle\Repository\UserRepository;
use Theodo\ThothCmsBundle\Tests\Unit;

use Doctrine\Common\DataFixtures\Loader;
use Theodo\ThothCmsBundle\DataFixtures\ORM\UserData;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\ORM\Query;

class UserRepositoryTest extends \PHPUnit_Framework_TestCase
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
