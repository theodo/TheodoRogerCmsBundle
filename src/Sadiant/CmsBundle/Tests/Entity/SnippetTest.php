<?php

require_once __DIR__.'/../../../../../app/AppKernel.php';

use Sadiant\CmsBundle\Entity\Snippet;
use Sadiant\CmsBundle\Tests\Unit;

use Doctrine\Common\DataFixtures\Loader;
use Sadiant\CmsBundle\DataFixtures\ORM\SnippetData;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;

class SnippetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    public function __construct()
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
     * Avoid warning, to delete
     */
    public function testNothing()
    {
    }
}
