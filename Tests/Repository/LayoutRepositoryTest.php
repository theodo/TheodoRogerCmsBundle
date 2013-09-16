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

use Theodo\RogerCmsBundle\Tests\Test as TestCase;
use Theodo\RogerCmsBundle\Entity\Layout;

class LayoutRepositoryTest extends TestCase
{
    public function setUp()
    {
        self::createRogerKernel();
    }

    /**
     * Test layout count
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-22
     */
    public function testCount()
    {
        // Retrieve entity manager
        $em = $this->getEntityManager();

        // Retrieve available snippets
        $layouts = $em->getRepository('Theodo\RogerCmsBundle\Entity\Layout')->findAll();

        // Test number of snippets
        $this->assertTrue(count($layouts) > 0);
    }
}
