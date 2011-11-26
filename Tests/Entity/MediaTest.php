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
 * @author Benjamin Grandfond <benjaming@theodo.fr>
 */
namespace Theodo\RogerCmsBundle\Tests\Entity;

require_once __DIR__.'/Test.php';

use Theodo\RogerCmsBundle\Tests\Entity\Test as TestCase;
use Theodo\RogerCmsBundle\Entity\Media;

class MediaTest extends TestCase
{
    /**
     * Test getPages function
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-22
     * Coming soon =)
     */
    public function testFullPath()
    {
        print_r("\n> Test \"getFullPath\" function");

        // Retrieve entity manager
        $em = $this->getEntityManager();

        // Retrieve media
        $media = $em->getRepository('TheodoRogerCmsBundle:Media')->findOneById(1);

        // Test full path
        $this->assertInstanceOf('Theodo\RogerCmsBundle\Entity\Media', $media);
        $this->assertEquals('uploads/picture.jpg', $media->getFullPath());
    }
}
