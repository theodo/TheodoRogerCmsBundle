<?php

require_once __DIR__.'/../../../../../app/AppKernel.php';

use Theodo\RogerCmsBundle\Entity\Page;
use Theodo\RogerCmsBundle\Repository\PageRepository;
use Theodo\RogerCmsBundle\Tests\Unit;

use Doctrine\Common\DataFixtures\Loader;
use Theodo\RogerCmsBundle\DataFixtures\ORM\PageData;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\ORM\Query;

class PageRepositoryTest extends \PHPUnit_Framework_TestCase
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
     * Test page status
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-20
     */
    public function testAvailableStatus()
    {
        print_r("\n> Test \"getAvailableStatus\" function");

        // Retrieve available status
        $availableStatus = PageRepository::getAvailableStatus();

        // Test type of return
        $this->assertInternalType('array', $availableStatus);

        // Test number of status
        $this->assertEquals(4, count($availableStatus));
    }

    /**
     * Test queryForMainPages function
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-20
     */
    public function testQueryForMainPages()
    {
        print_r("\n> Test \"queryForMainPages\" function");

        // Retrieve entity manager
        $em = $this->getEntityManager();

        // Retrieve "main" pages
        $pages = $em->getRepository('TheodoRogerCmsBundle:Page')->queryForMainPages()->getResult(Query::HYDRATE_OBJECT);

        // Check pages
        $this->assertInternalType('array', $pages);
        $this->assertEquals(1, count($pages));

        // Check first page
        $page = reset($pages);
        $this->assertInstanceOf('Theodo\RogerCmsBundle\Entity\Page', $page);
        $this->assertEquals('Homepage', $page->getName());
        $this->assertTrue(is_null($page->getParentId()));
    }

    /**
     *
     * Test getHomepage
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-28
     */
    public function testGetHomepage()
    {
        print_r("\n> Test \"getHomepage\" function");

        // Retrieve entity manager
        $em = $this->getEntityManager();

        // Retrieve the homepage
        $page = $em->getRepository('TheodoRogerCmsBundle:Page')->getHomepage();

        // check page
        $this->assertEquals('Homepage', $page->getName());
    }
}
