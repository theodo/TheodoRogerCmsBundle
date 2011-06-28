<?php

require_once __DIR__.'/../../../../../app/AppKernel.php';

use Theodo\TheodoCmsBundle\Entity\Snippet;
use Theodo\TheodoCmsBundle\Repository\SnippetRepository;
use Theodo\TheodoCmsBundle\Entity\Layout;
use Theodo\TheodoCmsBundle\Repository\LayoutRepository;
use Theodo\TheodoCmsBundle\Entity\Page;
use Theodo\TheodoCmsBundle\Repository\PageRepository;
use Theodo\TheodoCmsBundle\Tests\Unit;
use Theodo\TheodoCmsBundle\Extensions\Twig_Loader_Database;

class Twig_Loader_DatabaseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

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
     * Test page status
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-20
     */
    public function testGetSource()
    {
        print_r("\n> Test \"getSource\" function");
        
        $twig_loader_database = new Twig_Loader_Database($this->getEntityManager());
        
        $page = $this->getEntityManager()->getRepository('TheodoTheodoCmsBundle:Page')->findOneByName('theodo');

        $this->assertEquals($twig_loader_database->getSource('page:theodo'), $page->getContent());
        
        $layout = $this->getEntityManager()->getRepository('TheodoTheodoCmsBundle:Layout')->findOneByName('normal');

        $this->assertEquals($twig_loader_database->getSource('layout:normal'), $layout->getContent());
        
        $snippet = $this->getEntityManager()->getRepository('TheodoTheodoCmsBundle:Snippet')->findOneByName('bonsoir');

        $this->assertEquals($twig_loader_database->getSource('snippet:bonsoir'), $snippet->getContent());

        try {
            $twig_loader_database->getSource('doesnotexist');
            $this->fail('Exception missing');
        }
        catch (Twig_Error_Loader $expected) {
            $this->assertTrue(true);
        }
        //var_dump($expected->getMessage());

        try {
            $twig_loader_database->getSource('page:doesnotexist');
            $this->fail('Exception missing');
        }
        catch (Twig_Error_Loader $expected) {
            $this->assertTrue(true);    
        }
        //var_dump($expected->getMessage());
        
        try {
            $twig_loader_database->getSource('doesnotexist:bonsoir');
            $this->fail('Exception missing');
        }
        catch (Twig_Error_Loader $expected) {
            $this->assertTrue(true);
        }
        //var_dump($expected->getMessage());
        
    }

}
