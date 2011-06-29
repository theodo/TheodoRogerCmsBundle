<?php

require_once __DIR__.'/../../../../../app/AppKernel.php';

use Theodo\ThothCmsBundle\Entity\Snippet;
use Theodo\ThothCmsBundle\Repository\SnippetRepository;
use Theodo\ThothCmsBundle\Entity\Layout;
use Theodo\ThothCmsBundle\Repository\LayoutRepository;
use Theodo\ThothCmsBundle\Entity\Page;
use Theodo\ThothCmsBundle\Repository\PageRepository;
use Theodo\ThothCmsBundle\Tests\Unit;
use Theodo\ThothCmsBundle\Extensions\Twig_Loader_Repository;

class Twig_Loader_RepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Theodo\ThothCmsBundle\Extensions\Twig_Loader_Repository
     */
    protected $twig_loader;

    public function setUp()
    {
        // Load and boot kernel
        $kernel = new \AppKernel('test', true);
        $kernel->boot();

        // Load "test" entity manager
        $this->twig_loader = $kernel->getContainer()->get('thoth.twig.loader');
    }

    /**
     * TwigLoader getter
     *
     * @return \Theodo\ThothCmsBundle\Extensions\Twig_Loader_Repository
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
