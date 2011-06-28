<?php

require_once __DIR__.'/../../../../../app/AppKernel.php';

use Theodo\ThothCmsBundle\Entity\Snippet;
use Theodo\ThothCmsBundle\Tests\Unit;

use Doctrine\Common\DataFixtures\Loader;
use Theodo\ThothCmsBundle\DataFixtures\ORM\SnippetData;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\ORM\Query;

class SnippetRepositoryTest extends \PHPUnit_Framework_TestCase
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
        $snippets = $em->getRepository('Theodo\ThothCmsBundle\Entity\Snippet')->findAll();

        // Test number of snippets
        $this->assertTrue(count($snippets) > 0);
    }
}
