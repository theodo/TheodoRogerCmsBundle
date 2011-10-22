<?php

require_once __DIR__.'/../../../../../app/AppKernel.php';

use Theodo\RogerCmsBundle\Entity\Snippet;
use Theodo\RogerCmsBundle\Repository\SnippetRepository;
use Theodo\RogerCmsBundle\Entity\Layout;
use Theodo\RogerCmsBundle\Repository\LayoutRepository;
use Theodo\RogerCmsBundle\Entity\Page;
use Theodo\RogerCmsBundle\Repository\PageRepository;
use Theodo\RogerCmsBundle\Tests\Unit;
use Theodo\RogerCmsBundle\Extensions\Twig_Loader_Repository;

class Twig_Loader_RepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Theodo\RogerCmsBundle\Extensions\Twig_Loader_Repository
     */
    protected $twig_loader;

    public function setUp()
    {
        // Load and boot kernel
        $kernel = new \AppKernel('test', true);
        $kernel->boot();

        // Load "test" entity manager
        $this->twig_loader = $kernel->getContainer()->get('roger.twig.loader');
    }

    /**
     * TwigLoader getter
     *
     * @return \Theodo\RogerCmsBundle\Extensions\Twig_Loader_Repository
     */
    protected function getTwigLoader()
    {
        return $this->twig_loader;
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
        
        $source = $this->getTwigLoader()->getSource('page:homepage');
        $this->assertRegExp('/id="homepage"/', $source);

        $source = $this->getTwigLoader()->getSource('layout:normal');
        $this->assertRegExp('/<head>/', $source);
        
        $source = $this->getTwigLoader()->getSource('snippet:bonsoir');
        $this->assertRegExp('/Bonsoir !/', $source);

        try {
            $this->getTwigLoader()->getSource('doesnotexist');
            $this->fail('Exception missing');
        }
        catch (Twig_Error_Loader $expected) {
            $this->assertTrue(true);
        }
        
        try {
            $this->getTwigLoader()->getSource('layout:doesnotexist');
            $this->fail('Exception missing');
        }
        catch (Twig_Error_Loader $expected) {
            $this->assertTrue(true);
        }

        try {
            $this->getTwigLoader()->getSource('doesnotexist:bonsoir');
            $this->fail('Exception missing');
        }
        catch (Twig_Error_Loader $expected) {
            $this->assertTrue(true);
        }
    }
}
