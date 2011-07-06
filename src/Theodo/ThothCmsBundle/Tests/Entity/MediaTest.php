<?php

require_once __DIR__.'/../../../../../app/AppKernel.php';

use Theodo\TheodoCmsBundle\Entity\Media;
use Theodo\TheodoCmsBundle\Tests\Unit;

use Doctrine\Common\DataFixtures\Loader;
use Theodo\TheodoCmsBundle\DataFixtures\ORM\MediaData;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\ORM\Query;

class MediaTest extends \PHPUnit_Framework_TestCase
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
        $media = $em->getRepository('TheodoThothCmsBundle:Media')->findOneById(1);

        // Test full path
        $this->assertInstanceOf('Theodo\ThothCmsBundle\Entity\Media', $media);
        $this->assertEquals('uploads/picture.jpg', $media->getFullPath());
    }
}
