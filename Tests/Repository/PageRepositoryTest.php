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
 * Layout repository test class.
 *
 * @author Mathieu Dähne <mathieud@theodo.fr>
 * @author Benjamin Grandfond <benjaming@theodo.fr>
 */
namespace Theodo\RogerCmsBundle\Tests\Repository;

require_once __DIR__.'/../Test.php';

use Theodo\RogerCmsBundle\Tests\Test as TestCase;
use Theodo\RogerCmsBundle\Entity\Page;
use Theodo\RogerCmsBundle\Repository\PageRepository;
use Doctrine\ORM\Query;

class PageRepositoryTest extends TestCase
{
    public function setUp()
    {
        static::createRogerKernel();
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
