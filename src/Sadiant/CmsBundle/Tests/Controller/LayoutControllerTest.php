<?php

namespace Sadiant\CmsBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LayoutControllerTest extends WebTestCase
{
    /**
     * Test layout list
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-22
     */
    public function testList()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/admin/layouts');

        print_r("\n> LayoutController - Test list action");

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*Layouts.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*normal.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*Remove.*/', $client->getResponse()->getContent());
    }

    /**
     * Test new action
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-22
     */
    public function testNew()
    {
      $client = $this->createClient();
      $crawler = $client->request('GET', '/admin/layouts/new');

      print_r("\n> LayoutController - Test new action");

      $this->assertEquals(200, $client->getResponse()->getStatusCode());
      $this->assertRegexp('/.*New layout.*/', $client->getResponse()->getContent());
    }

    /**
     * Test layout edit
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-22
     */
    public function testEdit()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/admin/layouts/1/edit');

        print_r("\n> LayoutController - Test edit action");

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*Edit.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*name.*/', $client->getResponse()->getContent());
    }
}
