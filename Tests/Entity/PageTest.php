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
 * Media entity test class.
 *
 * @author Vincent Guillon <vincentg@theodo.fr>
 * @author Pierre-Henri Cumenge <pierrehenric@theodo.fr>
 */
namespace Theodo\RogerCmsBundle\Tests\Entity;

require_once __DIR__.'/Test.php';

use Theodo\RogerCmsBundle\Tests\Entity\Test as TestCase;
use Theodo\RogerCmsBundle\Entity\Page;

class PageTest extends TestCase
{
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
    public function testGetFullSlug()
    {
        // Retrieve entity manager
        $em = $this->getEntityManager();

        // Retrieve "home" page
        $page = $em->getRepository('TheodoRogerCmsBundle:Page')->findOneBy(array('slug' => 'homepage'));

        // Test full slug
        $this->assertEquals('homepage', $page->getFullSlug());

        // Retrieve "theodo-team" page
        $page = $em->getRepository('TheodoRogerCmsBundle:Page')->findOneBy(array('slug' => 'theodo-team'));

        // Test full slug
        $this->assertEquals('homepage/theodo/theodo-team', $page->getFullSlug());
    }

    /**
     * Test getFullSlug function
     *
     * @author Pierre-Henri Cumenge <pierrehenric@theodo.fr>
     * @since 2011-11-25
     */
    public function testI18NPageEdit()
    {
        // Retrieve entity manager
        $em = $this->getEntityManager();

        // Retrieve "home" page
        $page = $em->getRepository('TheodoRogerCmsBundle:Page')->findOneBy(array('slug' => 'homepage'));

        $page->setTitle('translated');

        // Retrieve "home" page
        $page = $em->getRepository('TheodoRogerCmsBundle:Page')->findOneBy(array('slug' => 'homepage'));

        var_dump($page->getTitle());
    }


}
