<?php

require_once __DIR__.'/../../../../../app/AppKernel.php';

use Sadiant\CmsBundle\Entity\Page;
use Sadiant\CmsBundle\Tests\Unit;

use Doctrine\Common\DataFixtures\Loader;
use Sadiant\CmsBundle\DataFixtures\ORM\PageData;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\ORM\Query;

class PageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    public function __construct()
    {
        $kernel = new \AppKernel('test', true);
        $kernel->boot();
        $this->em = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        $loader = new Loader();
        $loader->addFixture(new PageData());
        $purger = new ORMPurger();
        $executor = new ORMExecutor($this->em, $purger);
        $executor->execute($loader->getFixtures());
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
        // Retrieve entity manager
        $em = $this->getEntityManager();

        // Retrieve available status
        $availableStatus = $em->getRepository('SadiantCmsBundle:Page')->getAvailableStatus();

        // Test type of return
        $this->assertInternalType('array', $availableStatus);

        // Test number of status
        $this->assertEquals(4, count($availableStatus));
    }

    /**
     * Test getParent function
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-20
     */
    public function testGetParent()
    {
        // Retrieve entity manager
        $em = $this->getEntityManager();

        // Retrieve "about" page
        $aboutPage = $em->getRepository('SadiantCmsBundle:Page')->findOneBy(array('slug' => 'about'));

        // Test aboutPage
        $this->assertInstanceOf('Sadiant\CmsBundle\Entity\Page', $aboutPage);
        $this->assertEquals('About', $aboutPage->getName());
        
        // Retrieve parent page
        $parentPage = $aboutPage->getParent();
        $this->assertInstanceOf('Sadiant\CmsBundle\Entity\Page', $parentPage);
        $this->assertEquals('Homepage', $parentPage->getName());
    }

    /**
     * Test getChildren function
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-20
     */
    public function testGetChildren()
    {
        // Retrieve entity manager
        $em = $this->getEntityManager();

        // Retrieve "about" page
        $homepage = $em->getRepository('SadiantCmsBundle:Page')->findOneBy(array('slug' => 'homepage'));

        // Test hompepage
        $this->assertInstanceOf('Sadiant\CmsBundle\Entity\Page', $homepage);
        $this->assertEquals('Homepage', $homepage->getName());

        // Retrieve children pages
        $childrenPages = $homepage->getChildren();
        $this->assertInstanceOf('Doctrine\ORM\PersistentCollection', $childrenPages);
        $this->assertEquals(2, $childrenPages->count());
    }

    /**
     * Test queryForMainPages function
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-20
     */
    public function testQueryForMainPages()
    {
        // Retrieve entity manager
        $em = $this->getEntityManager();

        // Retrieve "main" pages
        $pages = $em->getRepository('SadiantCmsBundle:Page')->queryForMainPages()->getResult(Query::HYDRATE_OBJECT);

        // Check pages
        $this->assertInternalType('array', $pages);
        $this->assertEquals(1, count($pages));

        // Check first page
        $page = reset($pages);
        $this->assertInstanceOf('Sadiant\CmsBundle\Entity\Page', $page);
        $this->assertEquals('Homepage', $page->getName());
        $this->assertTrue(is_null($page->getParentId()));
    }
}
