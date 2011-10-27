<?php

namespace Theodo\RogerCmsBundle\Tests\Controller;

require_once __DIR__ . '/../../../../../app/AppKernel.php';

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Theodo\RogerCmsBundle\Repository\LayoutRepository;

class FrontendControllerTest extends WebTestCase
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
     * Test layout list
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-22
     */
    public function testPage()
    {
        print_r("\n> FrontendController - Test page action");

        $client = $this->createClient();
        $crawler = $client->request('GET', '/homepage');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*Lorem Ipsum.*/', $client->getResponse()->getContent());

        $link = $crawler->filterXPath('//a[@id="theodo-link"]')->link();
        $crawler = $client->click($link);

        // Test status and content
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*Theodo.*/', $client->getResponse()->getContent());

        $client = $this->createClient();
        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*Lorem Ipsum.*/', $client->getResponse()->getContent());

        $crawler = $client->request('GET', '/lala');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*404.*/', $client->getResponse()->getContent());
    }
}