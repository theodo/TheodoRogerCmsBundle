<?php

require_once __DIR__ . '/../../../../../app/AppKernel.php';

use Theodo\RogerCmsBundle\Tests\Unit;
use Theodo\RogerCmsBundle\Repository\ContentEntityRepository;
use Theodo\RogerCmsBundle\Entity\Layout;
use Theodo\RogerCmsBundle\Entity\Page;
use Theodo\RogerCmsBundle\Entity\Snippet;
use Theodo\RogerCmsBundle\DataFixtures\ORM\LayoutData;
use Theodo\RogerCmsBundle\DataFixtures\ORM\PageData;
use Theodo\RogerCmsBundle\DataFixtures\ORM\SnippetData;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\ORM\Query;

class ContentEntityRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;
    private $content_repository;

    public function setUp()
    {
        // Load and boot kernel
        $kernel = new \AppKernel('test', true);
        $kernel->boot();

        // Load "test" entity manager
        $this->em = $kernel->getContainer()->get('doctrine')->getEntityManager('test');
        $this->content_repository = $kernel->getContainer()->get('roger.content_repository');
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
     * ContentRepository getter
     *
     * @return Theodo\RogerCmsBundle\Repository\ContentEntityRepository
     */
    protected function getContentRepository()
    {
        return $this->content_repository;
    }

    /**
     * Test getSourceByName
     * 
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-29
     */
    public function testGetSourceByName()
    {
        print_r("\n> Test \"getSourceByName\" function");

        // Retrieve entity manager
        $em = $this->getEntityManager();
        $page = $em->getRepository('TheodoRogerCmsBundle:Page')->findOneBy(array('name' => 'Homepage'));

        $source = $this->getContentRepository()->getSourceByNameAndType($page->getName());

        $this->assertSame($source, $page->getContent());

        $source = $this->getContentRepository()->getSourceByNameAndType('Homepage', 'page');
        $this->assertRegExp('/id="homepage"/', $source);

        $source = $this->getContentRepository()->getSourceByNameAndType('normal', 'layout');
        $this->assertRegExp('/<head>/', $source);

        $source = $this->getContentRepository()->getSourceByNameAndType('bonsoir', 'snippet');
        $this->assertRegExp('/Bonsoir !/', $source);
    }

    /**
     * Test getHomePage
     * 
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-29
     */
    public function testGetHomePage()
    {
        print_r("\n> Test \"getHomePage\" function");

        // Retrieve entity manager
        $em = $this->getEntityManager();
        $page = $em->getRepository('TheodoRogerCmsBundle:Page')->findOneBy(array('name' => 'Homepage'));
        $homepage = $this->getContentRepository()->getHomePage();

        $this->assertSame($homepage->getId(), $page->getId());
    }

    /**
     * Test getPageBySlug
     * 
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-29
     */
    public function testGetPageBySlug()
    {
        print_r("\n> Test \"getPageBySlug\" function");

        // Retrieve entity manager
        $em = $this->getEntityManager();
        $page = $em->getRepository('TheodoRogerCmsBundle:Page')->findOneBy(array('slug' => 'theodo-team'));
        $page2 = $this->getContentRepository()->getPageBySlug('theodo-team');

        $this->assertSame($page->getId(), $page2->getId());
    }

    /**
     * Test getFirstTwoLevelPages
     * 
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-29
     */
    public function testGetFirstTwoLevelPages()
    {
        print_r("\n> Test \"getFirstTwoLevelPages\" function");

        // Retrieve pages
        $pages = $this->getContentRepository()->getFirstTwoLevelPages();
        $this->assertSame(1, count($pages));
        $this->assertSame('homepage', $pages[0]->getSlug());
        $this->assertTrue(0 < count($pages[0]->getChildren()));
    }

    /**
     * Test create
     * 
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-29
     */
    public function testCreate()
    {
        print_r("\n> Test \"create\" function");

        // Retrieve entity manager
        $em = $this->getContentRepository()->getEntityManager();

        // Start transaction
        $em->getConnection()->beginTransaction();

        // Test without parameter
        $this->assertSame(null, $this->getContentRepository()->create());

        // Create a new page
        $page = new Page();
        $page->setName('Content Entity Repository');
        $page->setContent('Test Content Entity Repository');
        $page->setBreadcrumb('Content Entity Repository');
        $page->setStatus('Published');
        $page->setPublishedAt(new \DateTime('now'));
        $page->setSlug('content-entity-repository');
        $page->setContentType('text/html');
        $page->setCacheable(true);
        $page->setPublic(false);

        // Save the page
        $this->getContentRepository()->create($page);

        // Test page
        $page = $em->getRepository('TheodoRogerCmsBundle:Page')->findOneBy(array('slug' => 'content-entity-repository'));
        $this->assertInstanceOf('\Theodo\RogerCmsBundle\Entity\Page', $page);

        // Rollback
        $em->getConnection()->rollback();
    }

    /**
     * Test remove
     * 
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-29
     */
    public function testRemove()
    {
        print_r("\n> Test \"Remove\" function");

        // Retrieve entity manager
        $em = $this->getContentRepository()->getEntityManager();

        // Start transaction
        $em->getConnection()->beginTransaction();

        // Test without parameter
        $this->assertSame(null, $this->getContentRepository()->remove());

        // Retrieve about page
        $page = $em->getRepository('TheodoRogerCmsBundle:Page')->findOneBy(array('slug' => 'about'));
        $this->assertInstanceOf('\Theodo\RogerCmsBundle\Entity\Page', $page);

        // Remove about page
        $this->getContentRepository()->remove($page);

        // Try to retrieve about page
        $page = $em->getRepository('TheodoRogerCmsBundle:Page')->findOneBy(array('slug' => 'about'));
        $this->assertSame(null, $page);

        // Rollback
        $em->getConnection()->rollback();
    }
}
