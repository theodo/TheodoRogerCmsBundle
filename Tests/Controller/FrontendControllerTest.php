<?php
/*
 * This file is part of the Roger CMS Bundle
 *
 * (c) Theodo <contact@theodo.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Theodo\RogerCmsBundle\Tests\Controller;

require_once __DIR__.'/../Test.php';

use Theodo\RogerCmsBundle\Tests\Test as WebTestCase;

/**
 * Frontend Controller test class.
 *
 * @author Mathieu Dähne <mathieud@theodo.fr>
 * @author Benjamin Grandfond <benjaming@theodo.fr>
 */
class FrontendControllerTest extends WebTestCase
{
    /**
     * Test layout list
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-22
     */
    public function testPage()
    {
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
