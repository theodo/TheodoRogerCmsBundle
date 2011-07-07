<?php

require_once __DIR__.'/../../../../../app/AppKernel.php';

use Theodo\ThothCmsBundle\Entity\Layout;
use Theodo\ThothCmsBundle\Tests\Unit;

use Doctrine\Common\DataFixtures\Loader;
use Theodo\ThothCmsBundle\DataFixtures\ORM\LayoutData;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\ORM\Query;

class LayoutRepositoryTest extends \PHPUnit_Framework_TestCase
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
        $layouts = $em->getRepository('Theodo\ThothCmsBundle\Entity\Layout')->findAll();

        // Test number of snippets
        $this->assertTrue(count($layouts) > 0);
    }
}
