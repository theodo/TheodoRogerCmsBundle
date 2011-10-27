<?php

require_once __DIR__.'/../../../../../app/AppKernel.php';

use Theodo\RogerCmsBundle\Entity\Page;
use Theodo\RogerCmsBundle\Tests\Unit;

use Doctrine\Common\DataFixtures\Loader;
use Theodo\RogerCmsBundle\DataFixtures\ORM\PageData;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\ORM\Query;

class PageTest extends \PHPUnit_Framework_TestCase
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
     * Test getParent function
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-20
     */
    public function testGetParent()
    {
        print_r("\n> Test \"getParent\" function");

        // Retrieve entity manager
        $em = $this->getEntityManager();

        // Retrieve "about" page
        $aboutPage = $em->getRepository('TheodoRogerCmsBundle:Page')->findOneBy(array('slug' => 'about'));

        // Test aboutPage
        $this->assertInstanceOf('Theodo\RogerCmsBundle\Entity\Page', $aboutPage);
        $this->assertEquals('About', $aboutPage->getName());
        
        // Retrieve parent page
        $parentPage = $aboutPage->getParent();
        $this->assertInstanceOf('Theodo\RogerCmsBundle\Entity\Page', $parentPage);
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
        print_r("\n> Test \"getChildren\" function");

        // Retrieve entity manager
        $em = $this->getEntityManager();

        // Retrieve "about" page
        $homepage = $em->getRepository('TheodoRogerCmsBundle:Page')->findOneBy(array('slug' => 'homepage'));

        // Test hompepage
        $this->assertInstanceOf('Theodo\RogerCmsBundle\Entity\Page', $homepage);
        $this->assertEquals('Homepage', $homepage->getName());

        // Retrieve children pages
        $childrenPages = $homepage->getChildren();
        $this->assertInstanceOf('Doctrine\ORM\PersistentCollection', $childrenPages);
    }

    /**
     * Test getFullSlug function
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-29
     */
    public function testGetFUllSlug()
    {
        print_r("\n> Test \"getFullSlug\" function");

        // Retrieve entity manager
        $em = $this->getEntityManager();

        // Retrieve "home" page
        $page = $em->getRepository('TheodoRogerCmsBundle:Page')->findOneBy(array('slug' => 'homepage'));

        // Test full slug
        $this->assertEquals($page->getFullSlug(), 'homepage');

        // Retrieve "theodo-team" page
        $page = $em->getRepository('TheodoRogerCmsBundle:Page')->findOneBy(array('slug' => 'theodo-team'));

        // Test full slug
        $this->assertEquals($page->getFullSlug(), 'homepage/theodo/theodo-team');
    }
}
