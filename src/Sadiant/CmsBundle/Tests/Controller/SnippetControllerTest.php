<?php

namespace Sadiant\CmsBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SnippetControllerTest extends WebTestCase
{
    /**
     * Test snippet list
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-22
     */
    public function testList()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/admin/snippets');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*Snippets.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*Add.*/', $client->getResponse()->getContent());
    }

    /**
     * Test snippet edit
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-22
     */
    public function testView()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/admin/snippets/1/edit');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
//        $this->assertRegexp('/.*Snippets.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*name.*/', $client->getResponse()->getContent());
    }
}
