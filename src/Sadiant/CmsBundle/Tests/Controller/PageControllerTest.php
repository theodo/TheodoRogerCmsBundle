<?php

namespace Sadiant\CmsBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PageControllerTest extends WebTestCase
{
    /**
     * Test index page
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-17
     */
    public function testIndex()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*Pages.*/', $client->getResponse()->getContent());
    }
}
