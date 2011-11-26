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
 * Layout Controller test class.
 *
 * @author Marek Kalnik <marekk@theodo.fr>
 * @author Benjamin Grandfond <benjaming@theodo.fr>
 */
namespace Theodo\RogerCmsBundle\Tests\Controller;

require_once __DIR__.'/../Test.php';

use Theodo\RogerCmsBundle\Tests\Test as WebTestCase;

class LayoutControllerTest extends WebTestCase
{
    /**
     * User connection
     *
     * @return Crawler
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-24
     */
    protected function login($client, $username = 'admin', $password = 'admin')
    {
        // Retrieve crawler
        $crawler = $client->request('GET', '/cms');

        // Select the login form
        $form = $crawler->filterXPath('//input[@name="login"]')->form();

        // Submit the form with valid credentials
        $crawler = $client->submit(
            $form,
            array(
                '_username'    => $username,
                '_password'    => $password,
                '_remember_me' => true
            )
        );

        // Response should be success
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        return $crawler;
    }

    /**
     * Logout user
     *
     * @return Crawler
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-24
     */
    protected function logout($client)
    {
        return $client->request('GET', '/cms/logout');
    }

    /**
     * Test layout list
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-22
     */
    public function testList()
    {
        print_r("\n> LayoutController - Test list action");

        $client = $this->createClient();

        // Connect user
        $crawler = $this->login($client);

        $crawler = $client->request('GET', '/cms/layouts');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*Layouts.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*normal.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*Remove.*/', $client->getResponse()->getContent());

        $this->logout($client);
    }

    /**
     * Test new action
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-22
     */
    public function testNew()
    {
        print_r("\n> LayoutController - Test new action");

        $client = $this->createClient();

        // Connect user
        $crawler = $this->login($client);

        $crawler = $client->request('GET', '/cms/layouts/new');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*New Layout.*/', $client->getResponse()->getContent());

        $this->logout($client);
    }

    /**
     * Test layout edit
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-22
     */
    public function testEdit()
    {
        print_r("\n> LayoutController - Test edit action");

        $client = $this->createClient();

        // Connect user
        $crawler = $this->login($client);

        $crawler = $client->request('GET', '/cms/layouts/1/edit');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*Edit.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*name.*/', $client->getResponse()->getContent());

        $this->logout($client);
    }

    /**
     * Test layout update
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-23
     */
    public function testUpdate()
    {
        print_r("\n> LayoutController - Test update action");

        $client = $this->createClient();

        // Connect user
        $crawler = $this->login($client);

        $crawler = $client->request('GET', '/cms/layouts/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*Edit.*/', $client->getResponse()->getContent());

        $this->logout($client);
    }

    /**
     * Test layout remove
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-23
     */
    public function testRemove()
    {
        print_r("\n> LayoutController - Test remove action");

        $client = $this->createClient();

        // Connect user
        $crawler = $this->login($client);

        $crawler = $client->request('GET', '/cms/layouts/1/remove');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*permanently remove.*/', $client->getResponse()->getContent());

        $this->logout($client);
    }

    /**
     * Test workflow
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-23
     */
    public function testWorkflow()
    {
        print_r("\n> LayoutController - Test workflow");

        // Start transaction
        static::$em->getConnection()->beginTransaction();

        $client = $this->createClient();

        // Connect user
        $crawler = $this->login($client);

        $crawler = $client->request('GET', '/cms/layouts');

        //Test status
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Retrieve "Add layout" link and click
        $link = $crawler->filterXPath('//a[@class="new-layout"]')->link();
        $crawler = $client->click($link);

        // Test status and content
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*New Layout.*/', $client->getResponse()->getContent());

        // Retrieve form
        $form = $crawler->filterXPath('//input[@name="save-and-edit"]')->form();

        // Submit form with errors
        $crawler = $client->submit($form, array());
        $crawler = $client->request('POST', $form->getUri());

        // Test return
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*cms\/layouts\/new$/', $client->getRequest()->getUri());
        $this->assertRegexp('/.*New Layout.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*This value should not be blank.*/', $client->getResponse()->getContent());

        // Submit valid form
        $crawler = $client->submit($form, array(
                    'layout[name]' => 'Functional test',
                    'layout[content]' => 'Functional test',
                ));

        // Test return
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*cms\/layouts\/.*\/edit$/', $client->getRequest()->getUri());
        $this->assertRegexp('/.*Edit.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*Functional test.*/', $client->getResponse()->getContent());

        // Update Form
        $form = $crawler->filterXPath('//input[@name="save-and-edit"]')->form();

        // Submit the form
        $crawler = $client->submit($form);

        // Test return
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertRegexp('/.*cms\/layouts\/.*\/edit$/', $client->getRequest()->getUri());
        $this->assertRegexp('/.*Edit.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*Functional test.*/', $client->getResponse()->getContent());

        // Back to admin homepage
        $link = $crawler->filterXPath('//a[@class="list-layout"]')->link();
        $crawler = $client->click($link);

        // Test status and content
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*Functional test.*/', $client->getResponse()->getContent());

        $link = $crawler->filterXPath('//a[@id="remove-layout-3"]')->link();
        $crawler = $client->click($link);

        // Test status and content
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*permanently remove.*/', $client->getResponse()->getContent());

        // Retrieve the delete form
        $form = $crawler->filterXPath('//input[@type="submit"]')->form();

        // Submit the form
        $crawler = $client->submit($form);

        // Test return
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertRegexp('/.*cms\/layouts$/', $client->getRequest()->getUri());

        static::$em->getConnection()->rollBack();

        $this->logout($client);
    }
}
