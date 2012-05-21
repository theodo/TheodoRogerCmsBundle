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
 * Content repository test class.
 *
 * @author Vincent Guillon <vincentg@theodo.fr>
 * @author Benjamin Grandfond <benjaming@theodo.fr>
 */
namespace Theodo\RogerCmsBundle\Tests\Repository;

require_once __DIR__.'/../Test.php';

use Theodo\RogerCmsBundle\Tests\Test as TestCase;
use Theodo\RogerCmsBundle\Entity\Page;

class ContentEntityRepositoryTest extends TestCase
{
    public function setUp()
    {
        self::createRogerKernel();
    }

    /**
     * Test getSourceByName
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-29
     */
    public function testGetSourceByName()
    {
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
