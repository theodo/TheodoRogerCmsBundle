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
 * Snippet repository test class.
 *
 * @author Mathieu Dähne <mathieud@theodo.fr>
 * @author Benjamin Grandfond <benjaming@theodo.fr>
 */
namespace Theodo\RogerCmsBundle\Tests\Repository;

require_once __DIR__.'/../Test.php';

use Theodo\RogerCmsBundle\Tests\Test as TestCase;
use Theodo\RogerCmsBundle\Entity\Snippet;
use Doctrine\ORM\Query;

class SnippetRepositoryTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        static::createRogerKernel();
    }

    /**
     * Test snippet count
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-20
     */
    public function testCount()
    {
        // Retrieve entity manager
        $em = $this->getEntityManager();

        // Retrieve available snippets
        $snippets = $em->getRepository('Theodo\RogerCmsBundle\Entity\Snippet')->findAll();

        // Test number of snippets
        $this->assertTrue(count($snippets) > 0);
    }
}
