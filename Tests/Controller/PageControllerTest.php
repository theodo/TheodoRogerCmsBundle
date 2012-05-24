<?php

/*
 * This file is part of the Roger CMS Bundle
 *
 * (c) Theodo <contact@theodo.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * Page Controller test class.
 *
 * @author Vincent Guillon <vincentg@theodo.fr>
 * @author Marek Kalnik <marekk@theodo.fr>
 * @author Benjamin Grandfond <benjaming@theodo.fr>
 */
namespace Theodo\RogerCmsBundle\Tests\Controller;

require_once __DIR__.'/../WebTestCase.php';

use Theodo\RogerCmsBundle\Tests\WebTestCase;
use Theodo\RogerCmsBundle\Repository\PageRepository;

class PageControllerTest extends WebTestCase
{
    /**
     * Test index action
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-17
     */
    public function testIndex()
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/admin/pages');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*Homepage.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*About.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*Theodo.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*Published.*/', $client->getResponse()->getContent());
    }

    /**
     * Test new action
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-22
     */
    public function testNew()
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/admin/pages/1/new');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*New Page.*/', $client->getResponse()->getContent());
    }

    /**
     * Test edit action
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-22
     */
    public function testEdit()
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/admin/pages/1/edit');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*Edit Page.*/', $client->getResponse()->getContent());
    }

    /**
     * Test update action
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-22
     */
    public function testUpdate()
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/admin/pages/1/update');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*Edit Page.*/', $client->getResponse()->getContent());
    }

    /**
     * Test workflow
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-22
     */
    public function testWorkflow()
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/admin/pages');

        // Test status
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Retrieve "Add child" link and click
        $link = $crawler->filterXPath('//a[@id="new-' . PageRepository::SLUG_HOMEPAGE . '-child"]')->link();
        $crawler = $client->click($link);

        // Test status
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Test page content
        $this->assertRegexp('/.*New Page.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*admin\/pages\/.*\/new$/', $client->getRequest()->getUri());

        // Retrieve form
        $form = $crawler->filterXPath('//input[@name="save-and-edit"]')->form();

        // Submit form with errors
        $crawler = $client->submit($form, array());
        $crawler = $client->request('POST', $form->getUri());

        // Test return
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*admin\/pages\/.*\/new$/', $client->getRequest()->getUri());
        $this->assertRegexp('/.*New Page.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*This value should not be blank.*/', $client->getResponse()->getContent());

        // Submit valid form
        $crawler = $client->submit($form, array(
            'page[parent_id]'    => static::$em->getRepository('TheodoRogerCmsBundle:Page')->findOneBy(array('slug' => PageRepository::SLUG_HOMEPAGE))->getId(),
            'page[name]'         => 'Functional test',
            'page[slug]'         => 'functional-test',
            'page[breadcrumb]'   => 'Functional test',
            'page[content]'      => '<p>Functional test page content</p>',
            'page[status]'       => PageRepository::STATUS_PUBLISH,
            'save-and-edit'      => true
        ));

        // Test return
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertRegexp('/.*admin\/pages\/.*\/edit$/', $client->getRequest()->getUri());
        $this->assertRegexp('/.*Edit Page.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*Functional test.*/', $client->getResponse()->getContent());

        // Update Form
        $form = $crawler->filterXPath('//input[@type="submit"]')->form();
        $form['page[published_at][year]'] = date('Y');
        $form['page[published_at][month]'] = date('n');
        $form['page[published_at][day]'] = date('j');

        // Submit the form
        $crawler = $client->submit($form);

        // Test return
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertRegexp('/.*admin\/pages$/', $client->getRequest()->getUri());
        $this->assertRegexp('/.*Functional test.*/', $client->getResponse()->getContent());

        // Back to admin homepage
        $crawler = $client->request('GET', '/admin/pages');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*Functional test.*/', $client->getResponse()->getContent());
    }
}
